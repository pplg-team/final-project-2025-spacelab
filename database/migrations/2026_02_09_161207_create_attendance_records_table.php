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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attendance_session_id');
            $table->foreign('attendance_session_id')
                ->references('id')
                ->on('attendance_sessions')
                ->onDelete('cascade');

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('selfie_photo')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
