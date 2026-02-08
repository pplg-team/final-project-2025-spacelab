<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\{
    DashboardController as StaffDashboardController,
    ScheduleController as StaffScheduleController,
    TermController as StaffTermController,
    MajorController as StaffMajorController,
    ClassroomController as StaffClassController,
    TeacherController as StaffTeacherController,
    StudentController as StaffStudentController,
    RoomController as StaffRoomController,
    BuildingController as StaffBuildingController,
    RoomHistoryController as StaffRoomHistoryController
};
use App\Http\Controllers\Staff\Term\BlockController as StaffBlockController;
use App\Http\Controllers\Staff\Major\CompanyRelationController as StaffCompanyRelationController;
use App\Http\Controllers\Staff\Major\RoleAssignmentController as StaffRoleAssignmentController;
use App\Http\Controllers\Staff\Classroom\JsonController as StaffClassJsonController;
use App\Http\Controllers\Staff\Classroom\GuardianController as StaffClassGuardianController;
use App\Http\Controllers\Staff\Classroom\StudentController as StaffClassStudentController;
use App\Http\Controllers\Staff\Classroom\ImportController as StaffClassroomImportController;
use App\Http\Controllers\Staff\Classroom\TemplateController as StaffClassroomTemplateController;
use App\Http\Controllers\Staff\Student\FetchController as StaffStudentFetchController;
use App\Http\Controllers\Staff\Student\ImportController as StaffStudentImportController;
use App\Http\Controllers\Staff\Major\ImportController as StaffMajorImportController;
use App\Http\Controllers\Staff\Student\TemplateController as StaffStudentTemplateController;
use App\Http\Controllers\Staff\Major\TemplateController as StaffMajorTemplateController;
use App\Http\Controllers\Staff\Teacher\ImportController as StaffTeacherImportController;
use App\Http\Controllers\Staff\Teacher\TemplateController as StaffTeacherTemplateController;
use App\Http\Controllers\Staff\SubjectController as StaffSubjectController;
use App\Http\Controllers\Staff\ReportController as StaffReportController;

Route::middleware(['auth', 'role:Staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('index');

        // Terms
        Route::get('/terms', [StaffTermController::class, 'index'])->name('terms.index');
        Route::post('/terms', [StaffTermController::class, 'store'])->name('terms.store');
        Route::get('/terms/{term}/edit', [StaffTermController::class, 'edit'])->name('terms.edit');
        Route::put('/terms/{term}', [StaffTermController::class, 'update'])->name('terms.update');
        Route::delete('/terms/{term}', [StaffTermController::class, 'destroy'])->name('terms.destroy');

        // Blocks
        Route::post('/blocks', [StaffBlockController::class, 'store'])->name('blocks.store');
        Route::get('/blocks/{block}/edit', [StaffBlockController::class, 'show'])->name('blocks.edit');
        Route::put('/blocks/{block}', [StaffBlockController::class, 'update'])->name('blocks.update');
        Route::delete('/blocks/{block}', [StaffBlockController::class, 'destroy'])->name('blocks.destroy');

        // Majors
        Route::get('/majors', [StaffMajorController::class, 'index'])->name('majors.index');
        Route::get('/majors/template', StaffMajorTemplateController::class)->name('majors.template');
        Route::get('/majors/create', [StaffMajorController::class, 'create'])->name('majors.create');
        Route::post('/majors', [StaffMajorController::class, 'store'])->name('majors.store');
        Route::get('/majors/{major}/edit', [StaffMajorController::class, 'edit'])->name('majors.edit');
        Route::put('/majors/{major}', [StaffMajorController::class, 'update'])->name('majors.update');
        Route::delete('/majors/{major}', [StaffMajorController::class, 'destroy'])->name('majors.destroy');

        Route::get('/majors/{major}', [StaffMajorController::class, 'show'])->name('majors.show');
        Route::post('/majors/{major}/company-relation', [StaffCompanyRelationController::class, 'store'])->name('majors.company-relation.store');
        Route::put('/majors/{major}/company-relation/{companyRelation}', [StaffCompanyRelationController::class, 'update'])->name('majors.company-relation.update');
        Route::delete('/majors/{major}/company-relation/{companyRelation}', [StaffCompanyRelationController::class, 'destroy'])->name('majors.company-relation.destroy');
        Route::put('/majors/{major}/role-assignment', [StaffRoleAssignmentController::class, 'update'])->name('majors.role-assignment.update');
        Route::post('/majors/import', StaffMajorImportController::class)->name('majors.import');

        // Classrooms
        Route::get('/classrooms', [StaffClassController::class, 'index'])->name('classrooms.index');
        Route::get('/classroomsjson/{id}', StaffClassJsonController::class)->name('classrooms.json');
        Route::get('/classrooms/template', StaffClassroomTemplateController::class)->name('classrooms.template');
        Route::post('/classrooms/import', StaffClassroomImportController::class)->name('classrooms.import');
        Route::post('/classrooms', [StaffClassController::class, 'store'])->name('classrooms.store');
        Route::get('/classrooms/{id}', [StaffClassController::class, 'show'])->name('classrooms.show');
        Route::put('/classrooms/{id}', [StaffClassController::class, 'update'])->name('classrooms.update');
        Route::delete('/classrooms/{id}', [StaffClassController::class, 'destroy'])->name('classrooms.destroy');
        Route::post('/classrooms/{id}/guardian', [StaffClassGuardianController::class, 'update'])->name('classrooms.guardian.update');
        Route::post('/classrooms/{id}/students', [StaffClassStudentController::class, 'store'])->name('classrooms.students.store');
        Route::delete('/classrooms/{id}/students/{studentId}', [StaffClassStudentController::class, 'destroy'])->name('classrooms.students.destroy');

        // Teachers
        Route::get('/teachers', [StaffTeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/template', StaffTeacherTemplateController::class)->name('teachers.template');
        Route::post('/teachers/import', StaffTeacherImportController::class)->name('teachers.import');
        Route::post('/teachers', [StaffTeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{id}', [StaffTeacherController::class, 'show'])->name('teachers.show');
        Route::put('/teachers/{id}', [StaffTeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/teachers/{id}', [StaffTeacherController::class, 'destroy'])->name('teachers.destroy');

        // Students
        Route::get('/students', [StaffStudentController::class, 'index'])->name('students.index');
        Route::get('/students/template', StaffStudentTemplateController::class)->name('students.template');
        Route::get('/students/fetch', StaffStudentFetchController::class)->name('students.fetch');
        Route::get('/students/{id}', [StaffStudentController::class, 'show'])->name('students.show');
        Route::post('/students', [StaffStudentController::class, 'store'])->name('students.store');
        Route::put('/students/{id}', [StaffStudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{id}', [StaffStudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/students/import', StaffStudentImportController::class)->name('students.import');

        // Subjects
        Route::get('/subjects/fetch', [StaffSubjectController::class, 'fetch'])->name('subjects.fetch');
        Route::get('/subjects', [StaffSubjectController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [StaffSubjectController::class, 'store'])->name('subjects.store');
        Route::put('/subjects/{subject}', [StaffSubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [StaffSubjectController::class, 'destroy'])->name('subjects.destroy');
        Route::put('/subjects/{subject}/majors', [StaffSubjectController::class, 'updateMajors'])->name('subjects.majors.update');
        Route::put('/subjects/{subject}/teachers', [StaffSubjectController::class, 'updateTeachers'])->name('subjects.teachers.update');

        // Buildings
        Route::post('/buildings', [StaffBuildingController::class, 'store'])->name('buildings.store');
        Route::get('/buildings/{building}', [StaffBuildingController::class, 'show'])->name('buildings.show');
        Route::put('/buildings/{building}', [StaffBuildingController::class, 'update'])->name('buildings.update');
        Route::delete('/buildings/{building}', [StaffBuildingController::class, 'destroy'])->name('buildings.destroy');

        // Rooms
        Route::get('/room-history', [StaffRoomHistoryController::class, 'index'])->name('rooms.history');
        Route::post('/room-history', [StaffRoomHistoryController::class, 'store'])->name('rooms.history.store');
        Route::put('/room-history/{id}', [StaffRoomHistoryController::class, 'update'])->name('rooms.history.update');
        Route::delete('/room-history/{id}', [StaffRoomHistoryController::class, 'destroy'])->name('rooms.history.destroy');
        Route::get('/rooms', [StaffRoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [StaffRoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}', [StaffRoomController::class, 'show'])->name('rooms.show');
        Route::put('/rooms/{room}', [StaffRoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [StaffRoomController::class, 'destroy'])->name('rooms.destroy');

        Route::get('/schedules', [StaffScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/major/{majorId}', [StaffScheduleController::class, 'getMajorSchedules'])->name('schedules.major');

        // Reports
        Route::get('/reports', [StaffReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/students', [StaffReportController::class, 'students'])->name('reports.students');
        Route::get('/reports/students/export', [StaffReportController::class, 'exportStudents'])->name('reports.students.export');
        Route::get('/reports/teachers', [StaffReportController::class, 'teachers'])->name('reports.teachers');
        Route::get('/reports/teachers/export', [StaffReportController::class, 'exportTeachers'])->name('reports.teachers.export');
    });

