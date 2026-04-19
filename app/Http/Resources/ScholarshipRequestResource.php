<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarshipRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = (float) $this->price;
        $percentage = (float) $this->discount_percentage;

        $discountAmount = $price * $percentage / 100;
        $finalPrice = max($price - $discountAmount, 0);

        return [
            'id' => $this->id,

            'user' => $this->whenLoaded('user'),
            'scholarship' => $this->whenLoaded('scholarship'),

            'title' => $this->title ?? $this->scholarship->title,
            'description' => $this->description ?? $this->scholarship->description,

            'price' => $price ?? $this->scholarship->price,
            'discount_percentage' => $percentage ?? $this->scholarship->discount_percentage,
            'discount_amount' => $discountAmount ?? $this->scholarship->discount_amount,
            'final_price' => $finalPrice ?? $this->scholarship->final_price,

            'duration' => $this->duration ?? $this->scholarship->duration,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
