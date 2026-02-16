<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    TimetableTemplate,
    TimetableEntry,
    Term,
    RoomHistory,
    TeacherSubject,
    Period,
    Room,
    Teacher
};

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('room_history')) {
            $this->command->warn('⚠️ Table room_history does not exist. Run RoomHistorySeeder first.');
            return;
        }

        $term = Term::where('is_active', true)->first() ?? Term::first();
        $templates = TimetableTemplate::where('is_active', true)->get();
        $teacherSubjects = TeacherSubject::all();
        $periods = Period::orderBy('start_time')->get();
        $rooms = Room::where('type', 'kelas')->get();
        $teachers = Teacher::all();

        if ($templates->isEmpty()) {
            $this->command->warn('⚠️ No timetable templates found. Run TimetableTemplateSeeder first.');
            return;
        }

        if ($teacherSubjects->isEmpty()) {
            $this->command->warn('⚠️ TeacherSubject data is required. Run TeacherSubjectSeeder first.');
            return;
        }

        if ($periods->isEmpty()) {
            $this->command->warn('⚠️ Period data is required. Run PeriodSeeder first.');
            return;
        }

        if ($rooms->isEmpty()) {
            $this->command->warn('⚠️ Classroom rooms are required. Run RoomSeeder first.');
            return;
        }

        if ($teachers->isEmpty()) {
            $this->command->warn('⚠️ Teachers are required. Run TeacherSeeder first.');
            return;
        }

        // gunakan is_teaching jika ada, fallback true jika tidak ada (tidak wajib menambah kolom)
        $teachingPeriods = $periods->filter(fn($p) => ($p->is_teaching ?? true))->values();

        if ($teachingPeriods->isEmpty()) {
            $this->command->warn('⚠️ No teaching periods found (check is_teaching flags).');
            return;
        }

        $roomHistory = RoomHistory::where('terms_id', $term->id)->get();
        $roomHistoryByClass = $roomHistory->keyBy('classes_id');

        $daysOfWeek = [1,2,3,4,5]; // Monday-Friday
        $periodIds = $teachingPeriods->pluck('id')->toArray();

        // Track teacher/room usage per slot to prevent conflicts
        $teacherSchedule = []; // "day:period" => [teacher_ids]
        $roomSchedule = [];    // "day:period" => [room_ids]

        $entriesCreated = 0;
        $entriesFailed = 0;

        // Pre-group teacherSubjects by teacher to ease conflict checks
        $teacherSubjectList = $teacherSubjects->values();

        foreach ($templates as $templateIndex => $template) {
            $classId = $template->class_id;

            // buat basis urutan teacherSubject yang acak untuk kelas ini
            $baseList = $teacherSubjectList->shuffle()->values();

            // Untuk menghindari pola yang sama tiap hari, kita akan merotasi daftar per hari
            foreach ($daysOfWeek as $dayIndex => $day) {
                // rotate offset tergantung hari (agar pattern berbeda tiap hari)
                $offset = $dayIndex % $baseList->count();
                $rotated = $baseList->slice($offset)->merge($baseList->slice(0, $offset))->values();

                // jika jumlah period lebih besar dari jumlah teacherSubject, kita perlu membuat urutan panjang.
                // aturan pengulangan: boleh ada pengulangan di dalam satu hari hanya jika periode berdampingan.
                $assignments = [];
                $tsCount = $rotated->count();

                for ($i = 0; $i < count($periodIds); $i++) {
                    // index dasar
                    $idx = $i % $tsCount;
                    $candidateTs = $rotated[$idx];

                    // jika pada posisi ini sama dengan yang sebelumnya (di hari yang sama),
                    // itu berarti akan jadi pengulangan adjacent (karena prev index = i-1). yang diperbolehkan.
                    // kita tetap akan mencoba alternatif jika guru sudah sibuk di slot ini.
                    $assignments[$periodIds[$i]] = $candidateTs;
                }

                // sekarang assign per period dengan pengecekan konflik guru/ruang
                foreach ($periodIds as $pIndex => $periodId) {
                    $slotKey = "{$day}:{$periodId}";

                    // start from planned candidate
                    $planned = $assignments[$periodId];

                    // find candidate yang tidak punya konflik di slot ini
                    $candidate = $this->findAvailableTeacherSubject($planned, $rotated, $teacherSchedule, $slotKey);

                    if (! $candidate) {
                        $entriesFailed++;
                        continue;
                    }

                    // find or create roomHistory untuk class
                    $roomHistEntry = $roomHistoryByClass->get($classId);

                    if ($roomHistEntry === null) {
                        $usedRooms = $roomSchedule[$slotKey] ?? [];
                        $availableRoom = $rooms->first(function($r) use ($usedRooms) {
                            return ! in_array($r->id, $usedRooms);
                        }) ?? $rooms->first();

                        $teacherForRoom = $teachers->random();

                        $roomHistEntry = RoomHistory::updateOrCreate(
                            [
                                'room_id' => $availableRoom->id,
                                'classes_id' => $classId,
                                'terms_id' => $term->id,
                                'event_type' => 'initial',
                            ],
                            [
                                'room_id' => $availableRoom->id,
                                'classes_id' => $classId,
                                'terms_id' => $term->id,
                                'event_type' => 'initial',
                                'teacher_id' => $teacherForRoom->id,
                                'user_id' => $teacherForRoom->user_id ?? null,
                                'start_date' => $term->start_date ?? now(),
                                'end_date' => $term->end_date ?? now()->addMonths(6),
                            ]
                        );

                        $roomHistoryByClass->put($classId, $roomHistEntry);
                    }

                    // jika room yang dipilih sudah terpakai di slot ini, coba alternatif roomHistory untuk kelas ini
                    $usedRooms = $roomSchedule[$slotKey] ?? [];
                    if (in_array($roomHistEntry->room_id, $usedRooms)) {
                        $alternatives = RoomHistory::where('classes_id', $classId)->get();
                        $found = $alternatives->first(function($alt) use ($usedRooms) {
                            return ! in_array($alt->room_id, $usedRooms);
                        });

                        if ($found) {
                            $roomHistEntry = $found;
                        } else {
                            $availableRoom = $rooms->first(function($r) use ($usedRooms) {
                                return ! in_array($r->id, $usedRooms);
                            }) ?? $rooms->first();

                            $teacherForRoom = $teachers->random();

                            $roomHistEntry = RoomHistory::updateOrCreate(
                                [
                                    'room_id' => $availableRoom->id,
                                    'classes_id' => $classId,
                                    'terms_id' => $term->id,
                                    'event_type' => 'initial',
                                ],
                                [
                                    'room_id' => $availableRoom->id,
                                    'classes_id' => $classId,
                                    'terms_id' => $term->id,
                                    'event_type' => 'initial',
                                    'teacher_id' => $teacherForRoom->id,
                                    'user_id' => $teacherForRoom->user_id ?? null,
                                    'start_date' => $term->start_date ?? now(),
                                    'end_date' => $term->end_date ?? now()->addMonths(6),
                                ]
                            );

                            $roomHistoryByClass->put($classId, $roomHistEntry);
                        }
                    }

                    // buat timetable entry
                    try {
                        TimetableEntry::create([
                            'template_id'         => $template->id,
                            'day_of_week'         => $day,
                            'period_id'           => $periodId,
                            'teacher_subject_id'  => $candidate->id,
                            'room_history_id'     => $roomHistEntry->id,
                        ]);

                        // mark teacher and room used in this slot
                        $teacherSchedule[$slotKey][] = $candidate->teacher_id;
                        $roomSchedule[$slotKey][]    = $roomHistEntry->room_id;

                        $entriesCreated++;
                    } catch (\Exception $e) {
                        $entriesFailed++;
                        $this->command->warn("Error creating entry for template {$template->id}: {$e->getMessage()}");
                    }
                } // end foreach period
            } // end foreach day
        } // end foreach template

        $this->command->info("✅ TimetableEntrySeeder: Created {$entriesCreated} entries, {$entriesFailed} failed.");
    }

    /**
     * Cari TeacherSubject yang available untuk slot (tidak scheduling conflict).
     * Prioritas: gunakan $preferred jika available, jika tidak carilah di $pool.
     *
     * @param \App\Models\TeacherSubject $preferred
     * @param \Illuminate\Support\Collection $pool
     * @param array $teacherSchedule
     * @param string $slotKey
     * @return \App\Models\TeacherSubject|null
     */
    protected function findAvailableTeacherSubject($preferred, $pool, $teacherSchedule, $slotKey)
    {
        $usedTeachers = $teacherSchedule[$slotKey] ?? [];

        // helper closure untuk cek apakah ts available
        $isAvailable = function($ts) use ($usedTeachers) {
            return ! in_array($ts->teacher_id, $usedTeachers);
        };

        if ($preferred && $isAvailable($preferred)) {
            return $preferred;
        }

        // cari alternatif di pool
        foreach ($pool as $ts) {
            if ($isAvailable($ts)) {
                return $ts;
            }
        }

        // sebagai fallback, coba cari teacherSubject lain di DB yang mungkin tidak ada di pool
        $alt = TeacherSubject::whereNotIn('teacher_id', $usedTeachers)->first();
        return $alt;
    }
}
