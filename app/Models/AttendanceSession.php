<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'timetable_entry_id',
        'user_id',
        'token',
        'start_time',
        'end_time',
        'is_active',
    ];

    public function timetableEntry()
    {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
    
}
