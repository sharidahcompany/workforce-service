<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendancePermissionResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),

            'approved_by' => $this->approved_by,
            'approved_by_user' => new UserResource($this->whenLoaded('approvedBy')),

            'deduct_from_balance' => $this->deduct_from_balance,
            'reason' => $this->reason,

            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,

            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ],

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
