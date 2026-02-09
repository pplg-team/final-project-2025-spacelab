<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;

class ScheduleController extends Controller
{
    public function index()
    {

        $activeTerm = Term::where('is_active', true)->first();

        return view('admin.schedules.index', [
            'activeTerm' => $activeTerm,
            'title' => 'Jadwal',
            'description' => 'Halaman jadwal',
        ]);
    }
}
