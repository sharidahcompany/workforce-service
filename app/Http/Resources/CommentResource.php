<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'body'=>$this->body,
            'attachment'=>$this->whenLoaded('media', function () {
                return $this->media
                    ->where('collection_name', 'attachment')
                    ->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'name' => $file->name,
                            'file_name' => $file->file_name,
                            'mime_type' => $file->mime_type,
                            'size' => $file->size,
                            'created_at' => $file->created_at?->format('Y-m-d H:i:s'),
                        ];
                    })
                    ->values();
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
