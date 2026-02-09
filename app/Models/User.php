<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, HasUuids, CanResetPassword;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password_hash',
        'role_id',
        'last_login_at',
    ];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Gunakan kolom password_hash untuk autentikasi.
     */
    public function getAuthPassword(): ?string
    {
        return $this->password_hash;
    }

    /**
     * Map virtual attribute 'password' ke kolom password_hash.
     */
    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['password_hash'] = $value
            ? (Hash::needsRehash($value) ? Hash::make($value) : $value)
            : null;
    }

    public function getPasswordAttribute(): ?string
    {
        return $this->password_hash;
    }

    /**
     * Kirim notifikasi reset password (agar test PasswordResetTest lulus).
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }


    public function student()
    {
        return $this->hasOne(Student::class, 'users_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'user_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }


    /**
     * Return the uppercase initials for the user's display name.
     */
    public function initials(): string
    {
        $name = trim($this->name ?? '');
        if ($name === '') return '';

        $parts = preg_split('/\s+/', $name);
        if (count($parts) === 1) {
            return strtoupper(substr($parts[0], 0, 1));
        }

        $first = strtoupper(substr($parts[0], 0, 1));
        $last = strtoupper(substr(end($parts), 0, 1));

        return $first . $last;
    }
}
