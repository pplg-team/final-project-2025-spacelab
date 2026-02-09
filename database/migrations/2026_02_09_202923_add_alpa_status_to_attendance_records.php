<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old constraint
        DB::statement("ALTER TABLE attendance_records DROP CONSTRAINT IF EXISTS attendance_records_status_check");
        
        // Add the new constraint including 'alpa'
        DB::statement("ALTER TABLE attendance_records ADD CONSTRAINT attendance_records_status_check CHECK (status::text = ANY (ARRAY['hadir'::text, 'izin'::text, 'sakit'::text, 'alpa'::text]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE attendance_records DROP CONSTRAINT IF EXISTS attendance_records_status_check");
        DB::statement("ALTER TABLE attendance_records ADD CONSTRAINT attendance_records_status_check CHECK (status::text = ANY (ARRAY['hadir'::text, 'izin'::text, 'sakit'::text]))");
    }
};
