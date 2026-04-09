<?php

namespace App\Models\Tenant;

use App\Enums\AttendanceStatus;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'shift_id',
        'attendance_date',
        'shift_start_at',
        'shift_end_at',
        'check_in',
        'check_out',
        'late_minutes',
        'overtime_minutes',
        'status',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'shift_start_at' => 'datetime',
        'shift_end_at' => 'datetime',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
        'status' => AttendanceStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
