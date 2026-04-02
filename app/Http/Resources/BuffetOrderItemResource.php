<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuffetOrderItemResource extends JsonResource
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

            'buffet_order_id' => $this->buffet_order_id,
            'buffet_item_id' => $this->buffet_item_id,

            'item' => BuffetItemResource::make(
                $this->whenLoaded('item')
            ),

            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'notes' => $this->notes,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
