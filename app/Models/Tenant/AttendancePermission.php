<?php

namespace App\Models\Tenant;

use App\Enums\MissionStatus;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendancePermission extends Model
{
    protected $fillable = [
        'user_id',
        'approved_by',
        'deduct_from_balance',
        'reason',
        'start_datetime',
        'end_datetime',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'approved_by' => 'integer',
        'deduct_from_balance' => 'boolean',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'status' => MissionStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
