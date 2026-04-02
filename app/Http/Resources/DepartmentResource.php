<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'name_localized' => $this->name[app()->getLocale()] ?? null,
            'name' => $this->name,
            'branch_id' => $this->branch_id,
            'parent_id' => $this->parent_id,
            'manager_id' => $this->manager_id,


            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id' => $this->branch->id,
                    'name' => $this->branch->name[app()->getLocale()] ?? null,
                ];
            }),

            'manager' => UserResource::make($this->whenLoaded('manager')),

            'parent' => $this->whenLoaded('parent', function () {
                return $this->parent ? [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name[app()->getLocale()] ?? null,
                ] : null;
            }),

            'users' => UserResource::collection($this->whenLoaded('users')),

            'children' => DepartmentResource::collection($this->whenLoaded('childrenRecursive')),

            'created_at' => $this->created_at,
        ];
    }
}
