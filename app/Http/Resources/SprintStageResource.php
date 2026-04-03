<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SprintStageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sprint_id' => $this->sprint_id,
            'name' => $this->name,
            'order' => $this->order,

            'sprint' => $this->whenLoaded('sprint', function () {
                return [
                    'id' => $this->sprint->id,
                    'name' => $this->sprint->name ?? null,
                ];
            }),

            'tasks' => TaskResource::collection(
                $this->whenLoaded('tasks')
            ),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
