<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function sprints()
    {
        return $this->hasMany(Sprint::class)->orderByDesc('id');
    }

    public function getStatusAttribute(): string
    {
        $sprints = $this->sprints;

        $allTasks = $sprints
            ->flatMap(fn($sprint) => $sprint->stages)
            ->flatMap(fn($stage) => $stage->tasks);

        $isLate = $this->end_date !== null && $this->end_date->isPast();

        if ($allTasks->isEmpty()) {
            return $isLate ? 'late' : 'pending';
        }

        $lastSprint = $sprints->sortByDesc('id')->first();

        if (!$lastSprint || $lastSprint->stages->isEmpty()) {
            return $isLate ? 'late' : 'in_progress';
        }

        $lastStage = $lastSprint->stages->sortByDesc('order')->first();

        $allTasksInLastStage = $allTasks->every(function ($task) use ($lastStage) {
            return $task->sprint_stage_id === $lastStage->id;
        });

        if ($allTasksInLastStage) {
            return 'complete';
        }

        return $isLate ? 'late' : 'in_progress';
    }
}
