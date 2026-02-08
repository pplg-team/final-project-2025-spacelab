<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\TimetableTemplate;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimetableTemplateController extends Controller
{
    /**
     * Display a listing of timetable templates.
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
        $selectedBlockId = $request->get('block_id');

        // Get classes based on major filter
        $classesQuery = Classroom::with('major')->orderBy('level')->orderBy('rombel');

        if ($selectedMajorId) {
            $classesQuery->where('major_id', $selectedMajorId);
        }

        $classes = $classesQuery->get();

        // Get templates with their classes
        $templatesQuery = TimetableTemplate::with(['class.major', 'block'])
            ->orderBy('version', 'desc');

        if ($selectedBlockId) {
            $templatesQuery->where('block_id', $selectedBlockId);
        }

        if ($selectedMajorId) {
            $templatesQuery->whereHas('class', function ($q) use ($selectedMajorId) {
                $q->where('major_id', $selectedMajorId);
            });
        }

        $templates = $templatesQuery->get()->groupBy(function ($template) {
            return $template->class_id;
        });

        return view('admin.schedules.templates.index', [
            'title' => 'Template Jadwal',
            'description' => 'Kelola template jadwal per kelas',
            'activeTerm' => $activeTerm,
            'majors' => $majors,
            'blocks' => $blocks,
            'classes' => $classes,
            'templates' => $templates,
            'selectedMajorId' => $selectedMajorId,
            'selectedBlockId' => $selectedBlockId,
        ]);
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|uuid|exists:classes,id',
            'block_id' => 'required|uuid|exists:blocks,id',
        ]);

        // Get the next version number for this class and block
        $maxVersion = TimetableTemplate::where('class_id', $validated['class_id'])
            ->where('block_id', $validated['block_id'])
            ->max('version') ?? 0;

        TimetableTemplate::create([
            'class_id' => $validated['class_id'],
            'block_id' => $validated['block_id'],
            'version' => $maxVersion + 1,
            'is_active' => false,
        ]);

        return redirect()->route('admin.schedules.templates.index', [
            'major_id' => $request->get('major_id'),
            'block_id' => $request->get('block_id'),
        ])->with('success', 'Template jadwal berhasil dibuat.');
    }

    /**
     * Activate a template (deactivate others for same class and block).
     */
    public function activate(TimetableTemplate $template)
    {
        DB::transaction(function () use ($template) {
            // Deactivate all other templates for the same class and block
            TimetableTemplate::where('class_id', $template->class_id)
                ->where('block_id', $template->block_id)
                ->where('id', '!=', $template->id)
                ->update(['is_active' => false]);

            // Activate this template
            $template->update(['is_active' => true]);
        });

        return redirect()->back()->with('success', 'Template jadwal berhasil diaktifkan.');
    }

    /**
     * Deactivate a template.
     */
    public function deactivate(TimetableTemplate $template)
    {
        $template->update(['is_active' => false]);

        return redirect()->back()->with('success', 'Template jadwal berhasil dinonaktifkan.');
    }

    /**
     * Remove the specified template.
     */
    public function destroy(TimetableTemplate $template)
    {
        // Check if template has entries
        if ($template->entries()->exists()) {
            return redirect()->back()
                ->with('error', 'Template tidak dapat dihapus karena masih memiliki entri jadwal.');
        }

        $template->delete();

        return redirect()->back()->with('success', 'Template jadwal berhasil dihapus.');
    }
}
