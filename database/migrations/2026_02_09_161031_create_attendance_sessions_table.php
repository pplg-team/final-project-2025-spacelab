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
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('timetable_entry_id');
            $table->foreign('timetable_entry_id')
                ->references('id')
                ->on('timetable_entries')
                ->onDelete('cascade');

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('token')->unique();
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
