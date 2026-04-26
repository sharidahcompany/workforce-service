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
            'parent_id'=>$this->parent_id,
            'user' => $this->whenLoaded('user', function () {
                return $this->user ? [
                    'id' => $this->user->id,
                    'full_name' => $this->user->full_name ?? null,
                    'avatar'=>$this->user->getFirstMediaUrl('avatar')
                ] : null;
             }),
            'user_id' => $this->user_id,

            'interviewer' => $this->whenLoaded('interviewer', function () {
                return $this->interviewer ? [
                    'id' => $this->interviewer->id,
                    'full_name' => $this->interviewer->full_name ?? null,
                    'avatar'=>$this->interviewer->getFirstMediaUrl('avatar')
                ] : null;
             }),
            'interviewer_id' => $this->interviewer_id,
            
            'scheduled_at' => $this->scheduled_at->format('Y-m-d H:i:s'),
            'meeting_link' => $this->meeting_link,
            'location' => $this->location,
            'reschedule_reason' => $this->reschedule_reason,
            'hr_notes' => $this->hr_notes,
            'interview_type' => $this->interview_type?->value ?? $this->interview_type,
            'interview_type_label' => $this->interview_type?->label() ?? $this->interview_type,
            'status' => $this->status?->value ?? $this->status,
            'status_label' => $this->status?->label() ?? $this->status,
            'children'=>JobInterviewResource::collection($this->whenLoaded('children')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
