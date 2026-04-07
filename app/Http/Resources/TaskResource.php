<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sprint_stage_id' => $this->sprint_stage_id,
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'priority' => $this->priority,
            'assigned_by' => $this->assigned_by,

            'start_date' => $this->start_date,
            'due_date' => $this->due_date?->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date?->format('Y-m-d H:i:s'),

            'sprint_stage' => $this->whenLoaded('sprintStage', function () {
                return [
                    'id' => $this->sprintStage->id,
                    'name' => $this->sprintStage->name ?? null,
                ];
            }),

            'assigned_by_user' => $this->whenLoaded('assignedBy', function () {
                return [
                    'id' => $this->assignedBy->id,
                    'full_name' => trim(($this->assignedBy->first_name ?? '') . ' ' . ($this->assignedBy->last_name ?? '')),
                    'email' => $this->assignedBy->email,
                ];
            }),

            'users' => $this->whenLoaded('users', function () {
                return UserResource::collection($this->users);
            }),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
