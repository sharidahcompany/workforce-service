<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'shift_id' => $this->shift_id,

            'attendance_date' => $this->attendance_date?->toDateString(),

            'shift_start_at' => $this->shift_start_at?->toISOString(),
            'shift_end_at' => $this->shift_end_at?->toISOString(),

            'check_in' => $this->check_in?->toISOString(),
            'check_out' => $this->check_out?->toISOString(),

            'late_minutes' => $this->late_minutes,
            'overtime_minutes' => $this->overtime_minutes,

            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),

            'user' => new UserResource($this->whenLoaded('user')),
            'shift' => new ShiftResource($this->whenLoaded('shift')),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
