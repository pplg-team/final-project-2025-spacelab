<?php

namespace App\Services;

use App\Models\TimetableEntry;
use App\Models\TimetableTemplate;

class TimetableConflictService
{
    /**
     * Cek bentrok guru: Guru tidak boleh mengajar di hari & jam yang sama,
     * walaupun beda kelas.
     *
     * @param string $teacherId
     * @param int $dayOfWeek
     * @param string $periodId
     * @param string $blockId
     * @param string|null $excludeEntryId Entry ID yang dikecualikan (untuk update)
     * @return TimetableEntry|null Entry yang bentrok, null jika tidak ada bentrok
     */
    public function checkTeacherConflict(
        string $teacherId,
        int $dayOfWeek,
        string $periodId,
        string $blockId,
        ?string $excludeEntryId = null
    ): ?TimetableEntry {
        $query = TimetableEntry::query()
            ->where('teacher_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->where('period_id', $periodId)
            ->whereHas('template', function ($q) use ($blockId) {
                $q->where('block_id', $blockId);
            });

        if ($excludeEntryId) {
            $query->where('id', '!=', $excludeEntryId);
        }

        return $query->with(['template.class', 'period', 'teacherSubject.subject'])->first();
    }

    /**
     * Cek bentrok ruangan: Ruangan tidak boleh dipakai di hari & jam yang sama
     * oleh kelas lain.
     *
     * @param string $roomHistoryId
     * @param int $dayOfWeek
     * @param string $periodId
     * @param string $blockId
     * @param string|null $excludeEntryId Entry ID yang dikecualikan (untuk update)
     * @return TimetableEntry|null Entry yang bentrok, null jika tidak ada bentrok
     */
    public function checkRoomConflict(
        string $roomHistoryId,
        int $dayOfWeek,
        string $periodId,
        string $blockId,
        ?string $excludeEntryId = null
    ): ?TimetableEntry {
        $query = TimetableEntry::query()
            ->where('room_history_id', $roomHistoryId)
            ->where('day_of_week', $dayOfWeek)
            ->where('period_id', $periodId)
            ->whereHas('template', function ($q) use ($blockId) {
                $q->where('block_id', $blockId);
            });

        if ($excludeEntryId) {
            $query->where('id', '!=', $excludeEntryId);
        }

        return $query->with(['template.class', 'period', 'teacherSubject.subject'])->first();
    }

    /**
     * Validasi entry jadwal, return array of conflict messages.
     *
     * @param array $data Data entry yang akan divalidasi
     * @param string|null $excludeEntryId Entry ID yang dikecualikan (untuk update)
     * @return array Array of error messages, empty jika tidak ada conflict
     */
    public function validateEntry(array $data, ?string $excludeEntryId = null): array
    {
        $errors = [];

        // Get block_id from template
        $template = TimetableTemplate::find($data['template_id']);
        if (!$template) {
            return ['Template jadwal tidak ditemukan.'];
        }

        $blockId = $template->block_id;

        // Check teacher conflict
        if (!empty($data['teacher_id'])) {
            $teacherConflict = $this->checkTeacherConflict(
                $data['teacher_id'],
                $data['day_of_week'],
                $data['period_id'],
                $blockId,
                $excludeEntryId
            );

            if ($teacherConflict) {
                $teacherName = $teacherConflict->teacherSubject?->teacher?->user?->name ?? 'Guru';
                $className = $teacherConflict->template?->class?->full_name ?? 'Kelas';
                $subjectName = $teacherConflict->teacherSubject?->subject?->name ?? 'Mapel';
                $periodName = $teacherConflict->period?->ordinal ?? '';

                $errors[] = "Bentrok Guru: {$teacherName} sudah mengajar {$subjectName} di kelas {$className} pada jam ke-{$periodName} di hari yang sama.";
            }
        }

        // Check room conflict
        if (!empty($data['room_history_id'])) {
            $roomConflict = $this->checkRoomConflict(
                $data['room_history_id'],
                $data['day_of_week'],
                $data['period_id'],
                $blockId,
                $excludeEntryId
            );

            if ($roomConflict) {
                $roomName = $roomConflict->roomHistory?->room?->name ?? 'Ruangan';
                $className = $roomConflict->template?->class?->full_name ?? 'Kelas';
                $subjectName = $roomConflict->teacherSubject?->subject?->name ?? 'Mapel';
                $periodName = $roomConflict->period?->ordinal ?? '';

                $errors[] = "Bentrok Ruangan: {$roomName} sudah digunakan oleh kelas {$className} untuk {$subjectName} pada jam ke-{$periodName} di hari yang sama.";
            }
        }

        return $errors;
    }
}
