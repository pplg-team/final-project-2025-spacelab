<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AttendanceAttachment extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'attendance_record_id',
        'file_path',
        'file_type',
    ];

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }
}
