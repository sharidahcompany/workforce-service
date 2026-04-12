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
            'job_post' => new JobPostResource($this->whenLoaded('jobPost')),
            'user' => new UserResource($this->whenLoaded('user')),
            'cv' => $this->getFirstMediaUrl('cv'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
