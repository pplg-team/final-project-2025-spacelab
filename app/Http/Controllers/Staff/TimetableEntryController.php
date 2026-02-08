<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\Period;
use App\Models\RoomHistory;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Term;
use App\Models\TimetableEntry;
use App\Models\TimetableTemplate;
use App\Services\TimetableConflictService;
use Illuminate\Http\Request;

class TimetableEntryController extends Controller
{
    protected TimetableConflictService $conflictService;

    public function __construct(TimetableConflictService $conflictService)
    {
        $this->conflictService = $conflictService;
    }

    /**
     * Display weekly schedule grid with filters.
     */
    public function index(Request $request)
    {
        $activeTerm = Term::where('is_active', true)->first();

        // Get all majors for filter
        $majors = Major::orderBy('name')->get();

        // Get blocks for active term
        $blocks = $activeTerm
            ? Block::where('terms_id', $activeTerm->id)->orderBy('name')->get()
            : collect();

        // Get selected filters
        $selectedMajorId = $request->get('major_id');
        $selectedClassId = $request->get('class_id');
        $selectedTemplateId = $request->get('template_id');

        // Get classes based on major filter
        $classes = collect();
        if ($selectedMajorId) {
            $classes = Classroom::where('major_id', $selectedMajorId)
                ->orderBy('level')
                ->orderBy('rombel')
                ->get();
        }

        // Get templates for selected class
        $templates = collect();
        if ($selectedClassId) {
            $templates = TimetableTemplate::where('class_id', $selectedClassId)
                ->with('block')
                ->orderBy('version', 'desc')
                ->get();
        }

        // Get selected template
        $selectedTemplate = null;
        $entries = collect();
        $periods = collect();

        if ($selectedTemplateId) {
            $selectedTemplate = TimetableTemplate::with(['class.major', 'block'])
                ->find($selectedTemplateId);

            if ($selectedTemplate) {
                // Get all periods (ordered by ordinal)
                $periods = Period::all();

                // Get entries for this template
                $entries = TimetableEntry::where('template_id', $selectedTemplateId)
                    ->with([
                        'period',
                        'teacherSubject.teacher.user',
                        'teacherSubject.subject',
                        'roomHistory.room'
                    ])
                    ->get()
                    ->groupBy(function ($entry) {
                        return $entry->day_of_week . '-' . $entry->period_id;
                    });
            }
        }

        // Data for the add/edit modal
        $teachers = Teacher::with('user')->get();
        $teacherSubjects = TeacherSubject::with(['teacher.user', 'subject'])->get();
        $roomHistories = RoomHistory::with('room')
            ->active()
            ->get();

        // Days of the week (Senin - Jumat)
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => "Jum'at",
        ];

        return view('staff.schedules.index', [
            'title' => 'Jadwal Pelajaran',
            'description' => 'Kelola jadwal pelajaran per kelas',
            'activeTerm' => $activeTerm,
            'majors' => $majors,
            'blocks' => $blocks,
            'classes' => $classes,
            'templates' => $templates,
            'selectedMajorId' => $selectedMajorId,
            'selectedClassId' => $selectedClassId,
            'selectedTemplateId' => $selectedTemplateId,
            'selectedTemplate' => $selectedTemplate,
            'periods' => $periods,
            'entries' => $entries,
            'days' => $days,
            'teachers' => $teachers,
            'teacherSubjects' => $teacherSubjects,
            'roomHistories' => $roomHistories,
        ]);
    }

    /**
     * Store a new timetable entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|uuid|exists:timetable_templates,id',
            'day_of_week' => 'required|integer|min:1|max:7',
            'period_id' => 'required|uuid|exists:periods,id',
            'teacher_subject_id' => 'required|uuid|exists:teacher_subjects,id',
            'room_history_id' => 'nullable|uuid|exists:room_history,id',
        ]);

        // Get teacher_id from teacher_subject for denormalized column
        $teacherSubject = TeacherSubject::find($validated['teacher_subject_id']);
        $validated['teacher_id'] = $teacherSubject->teacher_id;

        // Validate conflicts
        $conflicts = $this->conflictService->validateEntry($validated);

        if (!empty($conflicts)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => $conflicts]);
        }

        TimetableEntry::create($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Update an existing timetable entry.
     */
    public function update(Request $request, TimetableEntry $entry)
    {
        $validated = $request->validate([
            'teacher_subject_id' => 'required|uuid|exists:teacher_subjects,id',
            'room_history_id' => 'nullable|uuid|exists:room_history,id',
        ]);

        // Get teacher_id from teacher_subject for denormalized column
        $teacherSubject = TeacherSubject::find($validated['teacher_subject_id']);
        $validated['teacher_id'] = $teacherSubject->teacher_id;

        // Prepare data for conflict validation
        $conflictData = [
            'template_id' => $entry->template_id,
            'day_of_week' => $entry->day_of_week,
            'period_id' => $entry->period_id,
            'teacher_id' => $validated['teacher_id'],
            'room_history_id' => $validated['room_history_id'],
        ];

        // Validate conflicts (exclude current entry)
        $conflicts = $this->conflictService->validateEntry($conflictData, $entry->id);

        if (!empty($conflicts)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => $conflicts]);
        }

        $entry->update($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove a timetable entry.
     */
    public function destroy(TimetableEntry $entry)
    {
        $entry->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Get classes by major (for AJAX cascade filter).
     */
    public function getClassesByMajor(Request $request)
    {
        $majorId = $request->get('major_id');

        $classes = Classroom::where('major_id', $majorId)
            ->orderBy('level')
            ->orderBy('rombel')
            ->get()
            ->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->full_name,
                ];
            });

        return response()->json($classes);
    }

    /**
     * Get templates by class (for AJAX cascade filter).
     */
    public function getTemplatesByClass(Request $request)
    {
        $classId = $request->get('class_id');

        $templates = TimetableTemplate::where('class_id', $classId)
            ->with('block')
            ->orderBy('version', 'desc')
            ->get()
            ->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => 'Versi ' . $template->version . ' - ' . ($template->block->name ?? 'Block'),
                    'is_active' => $template->is_active,
                ];
            });

        return response()->json($templates);
    }
}
