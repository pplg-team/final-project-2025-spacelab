<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    ScheduleController as AdminScheduleController,
    TermController as AdminTermController,
    MajorController as AdminMajorController,
    ClassroomController as AdminClassController,
    TeacherController as AdminTeacherController,
    StudentController as AdminStudentController,
    RoomController as AdminRoomController,
    BuildingController as AdminBuildingController,
    RoomHistoryController as AdminRoomHistoryController,
    ReportController as AdminReportController
};
use App\Http\Controllers\Admin\Term\BlockController as AdminBlockController;
use App\Http\Controllers\Admin\Major\CompanyRelationController as AdminCompanyRelationController;
use App\Http\Controllers\Admin\Major\RoleAssignmentController as AdminRoleAssignmentController;
use App\Http\Controllers\Admin\Classroom\JsonController as AdminClassJsonController;
use App\Http\Controllers\Admin\Classroom\GuardianController as AdminClassGuardianController;
use App\Http\Controllers\Admin\Classroom\StudentController as AdminClassStudentController;
use App\Http\Controllers\Admin\Classroom\ImportController as AdminClassroomImportController;
use App\Http\Controllers\Admin\Classroom\TemplateController as AdminClassroomTemplateController;
use App\Http\Controllers\Admin\Student\FetchController as AdminStudentFetchController;
use App\Http\Controllers\Admin\Student\ImportController as AdminStudentImportController;
use App\Http\Controllers\Admin\Major\ImportController as AdminMajorImportController;
use App\Http\Controllers\Admin\Student\TemplateController as AdminStudentTemplateController;
use App\Http\Controllers\Admin\Major\TemplateController as AdminMajorTemplateController;
use App\Http\Controllers\Admin\Teacher\ImportController as AdminTeacherImportController;
use App\Http\Controllers\Admin\Teacher\TemplateController as AdminTeacherTemplateController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\TimetableTemplateController as AdminTimetableTemplateController;
use App\Http\Controllers\Admin\TimetableEntryController as AdminTimetableEntryController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\AttendanceSessionController as AdminAttendanceSessionController;

Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('index');

        // Terms
        Route::get('/terms', [AdminTermController::class, 'index'])->name('terms.index');
        Route::post('/terms', [AdminTermController::class, 'store'])->name('terms.store');
        Route::get('/terms/{term}/edit', [AdminTermController::class, 'edit'])->name('terms.edit');
        Route::put('/terms/{term}', [AdminTermController::class, 'update'])->name('terms.update');
        Route::delete('/terms/{term}', [AdminTermController::class, 'destroy'])->name('terms.destroy');

        // Blocks
        Route::post('/blocks', [AdminBlockController::class, 'store'])->name('blocks.store');
        Route::get('/blocks/{block}/edit', [AdminBlockController::class, 'show'])->name('blocks.edit');
        Route::put('/blocks/{block}', [AdminBlockController::class, 'update'])->name('blocks.update');
        Route::delete('/blocks/{block}', [AdminBlockController::class, 'destroy'])->name('blocks.destroy');

        // Majors
        Route::get('/majors', [AdminMajorController::class, 'index'])->name('majors.index');
        Route::get('/majors/template', AdminMajorTemplateController::class)->name('majors.template');
        Route::get('/majors/create', [AdminMajorController::class, 'create'])->name('majors.create');
        Route::post('/majors', [AdminMajorController::class, 'store'])->name('majors.store');
        Route::get('/majors/{major}/edit', [AdminMajorController::class, 'edit'])->name('majors.edit');
        Route::put('/majors/{major}', [AdminMajorController::class, 'update'])->name('majors.update');
        Route::delete('/majors/{major}', [AdminMajorController::class, 'destroy'])->name('majors.destroy');

        Route::get('/majors/{major}', [ AdminMajorController::class, 'show'])->name('majors.show');
        Route::post('/majors/{major}/company-relation', [AdminCompanyRelationController::class, 'store'])->name('majors.company-relation.store');
        Route::put('/majors/{major}/company-relation/{companyRelation}', [AdminCompanyRelationController::class, 'update'])->name('majors.company-relation.update');
        Route::delete('/majors/{major}/company-relation/{companyRelation}', [AdminCompanyRelationController::class, 'destroy'])->name('majors.company-relation.destroy');
        Route::put('/majors/{major}/role-assignment', [AdminRoleAssignmentController::class, 'update'])->name('majors.role-assignment.update');
        Route::post('/majors/import', AdminMajorImportController::class)->name('majors.import');

        // Classrooms
        Route::get('/classrooms', [AdminClassController::class, 'index'])->name('classrooms.index');
        Route::get('/classroomsjson/{id}', AdminClassJsonController::class)->name('classrooms.json');
        Route::get('/classrooms/template', AdminClassroomTemplateController::class)->name('classrooms.template');
        Route::post('/classrooms/import', AdminClassroomImportController::class)->name('classrooms.import');
        Route::post('/classrooms', [AdminClassController::class, 'store'])->name('classrooms.store');
        Route::get('/classrooms/{id}', [AdminClassController::class, 'show'])->name('classrooms.show');
        Route::put('/classrooms/{id}', [AdminClassController::class, 'update'])->name('classrooms.update');
        Route::delete('/classrooms/{id}', [AdminClassController::class, 'destroy'])->name('classrooms.destroy');
        Route::post('/classrooms/{id}/guardian', [AdminClassGuardianController::class, 'update'])->name('classrooms.guardian.update');
        Route::post('/classrooms/{id}/students', [AdminClassStudentController::class, 'store'])->name('classrooms.students.store');
        Route::delete('/classrooms/{id}/students/{studentId}', [AdminClassStudentController::class, 'destroy'])->name('classrooms.students.destroy');

        // Teachers
        Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/template', AdminTeacherTemplateController::class)->name('teachers.template');
        Route::post('/teachers/import', AdminTeacherImportController::class)->name('teachers.import');
        Route::post('/teachers', [AdminTeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{id}', [AdminTeacherController::class, 'show'])->name('teachers.show');
        Route::put('/teachers/{id}', [AdminTeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/teachers/{id}', [AdminTeacherController::class, 'destroy'])->name('teachers.destroy');

        // Students
        Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::get('/students/template', AdminStudentTemplateController::class)->name('students.template');
        Route::get('/students/fetch', AdminStudentFetchController::class)->name('students.fetch');
        Route::get('/students/{id}', [AdminStudentController::class, 'show'])->name('students.show');
        Route::post('/students', [AdminStudentController::class, 'store'])->name('students.store');
        Route::put('/students/{id}', [AdminStudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{id}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/students/import', AdminStudentImportController::class)->name('students.import');

        // Subjects
        Route::get('/subjects/fetch', [AdminSubjectController::class, 'fetch'])->name('subjects.fetch');
        Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [AdminSubjectController::class, 'store'])->name('subjects.store');
        Route::put('/subjects/{subject}', [AdminSubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [AdminSubjectController::class, 'destroy'])->name('subjects.destroy');
        Route::put('/subjects/{subject}/majors', [AdminSubjectController::class, 'updateMajors'])->name('subjects.majors.update');
        Route::put('/subjects/{subject}/teachers', [AdminSubjectController::class, 'updateTeachers'])->name('subjects.teachers.update');

        // Buildings
        Route::post('/buildings', [AdminBuildingController::class, 'store'])->name('buildings.store');
        Route::get('/buildings/{building}', [AdminBuildingController::class, 'show'])->name('buildings.show');
        Route::put('/buildings/{building}', [AdminBuildingController::class, 'update'])->name('buildings.update');
        Route::delete('/buildings/{building}', [AdminBuildingController::class, 'destroy'])->name('buildings.destroy');

        // Rooms
        Route::get('/room-history', [AdminRoomHistoryController::class, 'index'])->name('rooms.history');
        Route::post('/room-history', [AdminRoomHistoryController::class, 'store'])->name('rooms.history.store');
        Route::put('/room-history/{id}', [AdminRoomHistoryController::class, 'update'])->name('rooms.history.update');
        Route::delete('/room-history/{id}', [AdminRoomHistoryController::class, 'destroy'])->name('rooms.history.destroy');
        Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}', [AdminRoomController::class, 'show'])->name('rooms.show');
        Route::put('/rooms/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

        // Schedules - Main Entry Point
        Route::get('/schedules', [AdminTimetableEntryController::class, 'index'])->name('schedules.index');

        // Timetable Templates
        Route::get('/schedules/templates', [AdminTimetableTemplateController::class, 'index'])->name('schedules.templates.index');
        Route::post('/schedules/templates', [AdminTimetableTemplateController::class, 'store'])->name('schedules.templates.store');
        Route::post('/schedules/templates/{template}/activate', [AdminTimetableTemplateController::class, 'activate'])->name('schedules.templates.activate');
        Route::post('/schedules/templates/{template}/deactivate', [AdminTimetableTemplateController::class, 'deactivate'])->name('schedules.templates.deactivate');
        Route::delete('/schedules/templates/{template}', [AdminTimetableTemplateController::class, 'destroy'])->name('schedules.templates.destroy');

        // Timetable Entries
        Route::post('/schedules/entries', [AdminTimetableEntryController::class, 'store'])->name('schedules.entries.store');
        Route::put('/schedules/entries/{entry}', [AdminTimetableEntryController::class, 'update'])->name('schedules.entries.update');
        Route::delete('/schedules/entries/{entry}', [AdminTimetableEntryController::class, 'destroy'])->name('schedules.entries.destroy');

        // AJAX endpoints for cascading filters
        Route::get('/schedules/classes', [AdminTimetableEntryController::class, 'getClassesByMajor'])->name('schedules.classes');
        Route::get('/schedules/templates-by-class', [AdminTimetableEntryController::class, 'getTemplatesByClass'])->name('schedules.templates-by-class');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/students', [AdminReportController::class, 'students'])->name('reports.students');
        Route::get('/reports/students/export', [AdminReportController::class, 'exportStudents'])->name('reports.students.export');
        Route::get('/reports/teachers', [AdminReportController::class, 'teachers'])->name('reports.teachers');
        Route::get('/reports/teachers/export', [AdminReportController::class, 'exportTeachers'])->name('reports.teachers.export');
        Route::get('/reports/schedules', [AdminReportController::class, 'schedules'])->name('reports.schedules');
        Route::get('/reports/rooms', [AdminReportController::class, 'rooms'])->name('reports.rooms');

        // Staff Management
        Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [AdminStaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}', [AdminStaffController::class, 'show'])->name('staff.show');
        Route::put('/staff/{id}', [AdminStaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{id}', [AdminStaffController::class, 'destroy'])->name('staff.destroy');
        Route::post('/staff/{id}/reset-password', [AdminStaffController::class, 'resetPassword'])->name('staff.reset-password');

        // Attendance Sessions
        Route::get('/attendance', [AdminAttendanceSessionController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [AdminAttendanceSessionController::class, 'store'])->name('attendance.store');
        Route::delete('/attendance/{session}', [AdminAttendanceSessionController::class, 'destroy'])->name('attendance.destroy');
    });

