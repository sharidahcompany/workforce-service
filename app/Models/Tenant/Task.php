<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = [
        'sprint_stage_id',
        'name',
        'description',
        'order',
        'priority',
        'assigned_by',
        'start_date',
        'due_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function sprintStage()
    {
        return $this->belongsTo(SprintStage::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
