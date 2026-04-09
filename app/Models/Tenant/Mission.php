<?php

namespace App\Models\Tenant;

use App\Enums\MissionApprovalStatus;
use App\Enums\MissionStatus;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mission extends Model
{
    protected $fillable = [
        'approved_by',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'actual_start_datetime',
        'actual_end_datetime',
        'created_by',
        'expense_amount',
    ];

    protected $casts = [
        'approved_by' => 'integer',
        'created_by' => 'integer',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'actual_start_datetime' => 'datetime',
        'actual_end_datetime' => 'datetime',
        'expense_amount' => 'decimal:2',
        'status' => MissionStatus::class,
        'approval_status' => MissionApprovalStatus::class,
    ];

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mission_user')
            ->withTimestamps();
    }
}
