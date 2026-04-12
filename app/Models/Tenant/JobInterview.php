<?php

namespace App\Models\Tenant;

use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobInterview extends Model
{
    protected $fillable = [
        'user_id',
        'interviewer_id',
        'interview_type',
        'scheduled_at',
        'meeting_link',
        'location',
        'reschedule_reason',
        'technical_score',
        'communication_score',
        'attitude_score',
        'overall_score',
        'hr_notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'interview_type' => InterviewType::class,
        'status' => InterviewStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
