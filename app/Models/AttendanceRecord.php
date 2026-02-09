<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;


class AttendanceRecord extends Model
{
    use HasUuids;
    //
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'attendance_session_id',
        'user_id',
        'status',
        'latitude',
        'longitude',
        'selfie_photo',
        'note',
        'scanned_at',
    ];

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(AttendanceAttachment::class);
    }
}
