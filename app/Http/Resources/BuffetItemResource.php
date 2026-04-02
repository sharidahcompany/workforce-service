<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuffetItemResource extends JsonResource
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

            'buffet_id' => $this->buffet_id,
            'buffet' => BuffetResource::make(
                $this->whenLoaded('buffet')
            ),

            'name_localized' => $this->name[$locale] ?? null,
            'name' => $this->name,

            'description_localized' => $this->description[$locale] ?? null,
            'description' => $this->description,

            'price' => $this->price,
            'is_active' => $this->is_active,

            'image' => $this->getFirstMediaUrl('buffet_items'),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
