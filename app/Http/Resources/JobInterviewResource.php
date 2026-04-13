<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobInterviewResource extends JsonResource
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
            'user_id' => $this->user_id,
            'interviewer_id' => $this->interviewer_id,
            'interview_type' => $this->interview_type?->value ?? $this->interview_type,
            'scheduled_at' => $this->scheduled_at,
            'meeting_link' => $this->meeting_link,
            'location' => $this->location,
            'reschedule_reason' => $this->reschedule_reason,
            'technical_score' => $this->technical_score,
            'communication_score' => $this->communication_score,
            'attitude_score' => $this->attitude_score,
            'overall_score' => $this->overall_score,
            'hr_notes' => $this->hr_notes,
            'status' => $this->status?->value ?? $this->status,
            'status_label' => $this->status?->label() ?? $this->status,
            'interview_type_label' => $this->interview_type?->label() ?? $this->interview_type,
            'user' => new UserResource($this->whenLoaded('user')),
            'interviewer' => new UserResource($this->whenLoaded('interviewer')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
