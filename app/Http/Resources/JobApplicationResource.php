<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_post_id' => $this->job_post_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'full_name' => $this->full_name,
            'id_number' => $this->id_number,
            'nationality' => $this->nationality,
            'address' => $this->address,
            'job_post' => new JobPostResource($this->whenLoaded('jobPost')),
            'user' => new UserResource($this->whenLoaded('user')),
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'interviews' => JobInterviewResource::collection($this->whenLoaded('interviews')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
