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
        Schema::create('attendance_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attendance_record_id');
            $table->foreign('attendance_record_id')
                ->references('id')
                ->on('attendance_records')
                ->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attandance_attachments');
    }
};
