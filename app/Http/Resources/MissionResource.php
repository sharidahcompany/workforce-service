<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'approved_by' => $this->approved_by,
            'approved_by_user' => new UserResource($this->whenLoaded('approvedBy')),
            'title' => $this->title,
            'description' => $this->description,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'actual_start_datetime' => $this->actual_start_datetime,
            'actual_end_datetime' => $this->actual_end_datetime,
            'created_by' => $this->created_by,
            'created_by_user' => new UserResource($this->whenLoaded('createdBy')),
            'expense_amount' => $this->expense_amount,
            'assignees' => UserResource::collection($this->whenLoaded('assignees')),
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ],

            'approval_status' => [
                'value' => $this->approval_status?->value,
                'label' => $this->approval_status?->label(),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
