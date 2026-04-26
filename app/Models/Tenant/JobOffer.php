<?php

namespace App\Models\Tenant;

use App\Enums\ApprovalStatus;
use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobOffer extends Model
{
    protected $fillable = [
        'career_id',
        'job_application_id',
        'user_id',
        'status',
    ];


// start Relationship
   
    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class, 'career_id','id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

// end Relationship
    protected $casts = [
        'job_application_id'=>'integer',
        'career_id'=>'integer',
        'user_id'=>'integer',
        'status'=>ApprovalStatus::class,
    ];
}
