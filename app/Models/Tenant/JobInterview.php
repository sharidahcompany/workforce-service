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
        'parent_id',
        'user_id',
        'interviewer_id',
        'scheduled_at',
        'meeting_link',
        'location',
        'reschedule_reason',
        'hr_notes',
        'interview_type',
        'status',
    ];


// start Relationship
    public function parent(){
        return $this->belongsTo(JobInterview::class,'parent_id','id');
    }
    public function children(){
        return $this->hasMany(JobInterview::class,'parent_id','id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
// end Relationship

    protected $casts = [
        'parent_id'=>'integer',
        'user_id'=>'integer',
        'interviewer_id'=>'integer',
        'scheduled_at' => 'datetime',
        'interview_type' => InterviewType::class,
        'status' => InterviewStatus::class,
    ];


}
