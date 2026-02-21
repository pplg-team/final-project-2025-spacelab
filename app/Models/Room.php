<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\RoomHistory;
use App\Models\TimetableEntry;

class Room extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'code', 'name', 'building_id', 'floor', 'capacity', 'type', 'is_active', 'notes'
    ];

    public function timetableEntries(): HasManyThrough
    {
        // TimetableEntry is related to Room via RoomHistory
        return $this->hasManyThrough(
            TimetableEntry::class,
            RoomHistory::class,
            'room_id', // Foreign key on RoomHistory table...
            'room_history_id', // Foreign key on TimetableEntry table...
            'id', // Local key on rooms table
            'id' // Local key on room_history table
        );
    }

    public function scheduleEntries(): HasManyThrough
    {
        return $this->timetableEntries();
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
}
