<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,

            'name_localized' => $this->name[$locale] ?? null,
            'name' => $this->name,

            'code' => $this->code,

            'building_number' => $this->building_number,
            'street' => $this->street,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,

            'full_address' => trim(implode(', ', array_filter([
                $this->building_number,
                $this->street,
                $this->city,
            ]))),

            'manager_id' => $this->manager_id,

            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => (int) $this->radius,

            'manager' => $this->whenLoaded('manager', function () {
                return new UserResource($this->manager);
            }),

            'departments' => $this->whenLoaded('departments', function () {
                return DepartmentResource::collection($this->departments);
            }),

            'users' => $this->whenLoaded('users', function () {
                return UserResource::collection($this->users);
            }),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
