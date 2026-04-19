<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScholarshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = (float) $this->price;
        $discountPercentage = (float) $this->discount_percentage;
        $discountAmount = $price * $discountPercentage / 100;
        $finalPrice = max($price - $discountAmount, 0);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,

            'price' => $price,

            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'dicount_percentage' => $discountPercentage,
            'final_price' => $finalPrice,

            'duration' => $this->duration,
            'is_active' => $this->is_active,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
