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
        Schema::table('rooms', function (Blueprint $table) {
            //
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('building', function (Blueprint $table) {
            //
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
        Schema::table('room', function (Blueprint $table) {
            //
            $table->dropForeign(['building_id']);
            $table->dropColumn('building_id');
        });
    }
};
