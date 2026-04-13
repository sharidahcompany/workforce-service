<?php

namespace App\Http\Resources;

use App\Enums\WeekDay;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
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
            'name' => $this->name,
            'work_days' => collect($this->work_days ?? [])->map(fn($day) => [
                'value' => $day,
                'label' => WeekDay::from($day)->label(),
            ])->values(),
            'start_time' => $this->start_time->format('H:i'),
            'end_time' => $this->end_time->format('H:i'),
            'grace_period' => $this->grace_period,
            'overtime_allowed' => $this->overtime_allowed,
            'overtime_rate' => $this->overtime_rate,
            'deduction_rate' => $this->deduction_rate,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'user_ids' => $this->whenLoaded('users', fn() => $this->users->pluck('id')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
