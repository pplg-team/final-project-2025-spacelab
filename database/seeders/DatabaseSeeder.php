<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::disableQueryLog();
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UpdateSiswaPasswordSeeder::class,
            // BuildingSeeder::class,
            // RoomSeeder::class,
            // SubjectSeeder::class,
            // PeriodSeeder::class,
            // TermSeeder::class,
            // BlockSeeder::class,
            // MajorSeeder::class,
            // ClassSeeder::class,
            // TeacherSeeder::class,
            // RoleAssignmentSeeder::class,
            // StudentSeeder::class,
            // TeacherSubjectSeeder::class,
            // ClassHistorySeeder::class,
            // RoomHistorySeeder::class,
            // GuardianClassHistorySeeder::class,
            // TimetableTemplateSeeder::class,
            // TimetableSeeder::class,
            // CompanySeeder::class,
            // CompanyRelationSeeder::class,
            // SubjectMajorAllowedSeeder::class,
            // MajorSubjectSeeder::class,
            // NotificationSeeder::class,
            // ImportJobSeeder::class,
            // AuditLogSeeder::class,
            // ReportSeeder::class,
        ]);
    }
}
