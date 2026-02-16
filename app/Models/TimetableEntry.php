<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimetableEntry extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['template_id', 'day_of_week', 'period_id', 'teacher_subject_id', 'room_history_id'];
    protected $casts = [
        'day_of_week' => 'integer'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(TimetableTemplate::class, 'template_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function teacherSubject(): BelongsTo
    {
        return $this->belongsTo(TeacherSubject::class, 'teacher_subject_id');
    }

    public function roomHistory(): BelongsTo
    {
        return $this->belongsTo(RoomHistory::class, 'room_history_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }



    public function getDayNameAttribute(): string
    {
        return match ($this->day_of_week) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => "Jum'at",
            6 => 'Sabtu',
            7 => 'Minggu',
            default => 'Unknown',
        };
    }


    public function getSubjectAttribute()
    {
        return $this->teacherSubject?->subject;
    }


    public function getTeacherAttribute()
    {
        return $this->teacherSubject?->teacher;
    }

    public function getClassroomAttribute()
    {
        return $this->template?->class;
    }

    public function getRoomAttribute()
    {
        return $this->roomHistory?->room;
    }

    public function isOngoing(?Carbon $now = null): bool
    {
        $now = $now ?? Carbon::now();
        $period = $this->period;
        if (! $period) {
            return false;
        }

        if ($period->start_time && $period->end_time) {
            $startTime = Carbon::createFromFormat('H:i:s', $period->start_time, $now->getTimezone())->setDate($now->year, $now->month, $now->day);
            $endTime = Carbon::createFromFormat('H:i:s', $period->end_time, $now->getTimezone())->setDate($now->year, $now->month, $now->day);
        } else {
            if (! $period->start_date || ! $period->end_date) {
                return false;
            }
            if (! ($now->greaterThanOrEqualTo($period->start_date) && $now->lessThan($period->end_date))) {
                return false;
            }

            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d').' '.$period->start_date->format('H:i:s'), $now->getTimezone());
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d').' '.$period->end_date->format('H:i:s'), $now->getTimezone());
        }

            if ($endTime->lessThanOrEqualTo($startTime)) {
                $endTime->addDay();
                $startPrevious = $startTime->copy()->subDay();
                $endPrevious = $endTime->copy()->subDay();

                if ($now->between($startPrevious, $endPrevious) || $now->between($startTime, $endTime)) {
                    return true;
                }

                return false;
            }

            return $now->greaterThanOrEqualTo($startTime) && $now->lessThan($endTime);
        }


    public function isPast(?Carbon $now = null): bool
    {
        $now = $now ?? Carbon::now();
        $period = $this->period;
        if (! $period) {
            return false;
        }
        if ($period->end_time) {
            $endTime = Carbon::createFromFormat('H:i:s', $period->end_time, $now->getTimezone())->setDate($now->year, $now->month, $now->day);
            $startTime = null;
            if ($period->start_time) {
                $startTime = Carbon::createFromFormat('H:i:s', $period->start_time, $now->getTimezone())->setDate($now->year, $now->month, $now->day);
                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $endTime->addDay();
                }
            }

            return $now->greaterThanOrEqualTo($endTime);
        }

        if ($period->isPast($now)) {
            return true;
        }

        return false;
    }
}
