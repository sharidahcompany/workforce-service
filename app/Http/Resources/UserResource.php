<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'full_name' => $this->full_name,
            'id_number' => $this->id_number,
            'address' => $this->address,
            'nationality' => $this->nationality,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            'email' => $this->email,
            'job' => new CareerResource($this->whenLoaded('job')),
            'branch_id' => $this->branch_id,

            'branch' => $this->whenLoaded('branch', function () {
                return new BranchResource($this->branch);
            }),

            'departments' => $this->whenLoaded('departments', function () {
                return DepartmentResource::collection($this->departments);
            }),

            'experiences' => ExperienceResource::collection($this->experiences),

            'avatar' => $this->getFirstMediaUrl('avatar'),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
