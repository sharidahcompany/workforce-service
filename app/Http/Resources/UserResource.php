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
            'email' => $this->email,
            'phone' => $this->phone,
            'id_number' => $this->id_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'address' => $this->address,
            'nationality' => $this->nationality,
            'date_of_birth' => $this->date_of_birth,
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                ];
            }),
            'department' => $this->whenLoaded('department', function () {
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                ];
            }),
            'career' => $this->whenLoaded('career', function () {
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                ];
            }),
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'avatar' => $this->getFirstMediaUrl('avatar'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),





            // 'id' => $this->id,
            // 'email' => $this->email,
            // 'phone' => $this->phone,
            // 'id_number' => $this->id_number,
            // 'first_name' => $this->first_name,
            // 'last_name' => $this->last_name,
            // 'full_name' => $this->full_name,
            // 'address' => $this->address,
            // 'nationality' => $this->nationality,
            // 'date_of_birth' => $this->date_of_birth,

            // 'name' => trim($this->first_name . ' ' . $this->last_name),
            // 'job' => new CareerResource($this->whenLoaded('job')),
            // 'branch_id' => $this->branch_id,

            // 'branch' => $this->whenLoaded('branch', function () {
            //     return new BranchResource($this->branch);
            // }),

            // 'departments' => $this->whenLoaded('departments', function () {
            //     return DepartmentResource::collection($this->departments);
            // }),

            // 'experiences' => $this->whenLoaded('experiences', function () {
            //     return ExperienceResource::collection($this->experiences);
            // }),

            // 'avatar' => $this->getFirstMediaUrl('avatar'),

            // 'created_at' => $this->created_at?->toDateTimeString(),
            // 'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
