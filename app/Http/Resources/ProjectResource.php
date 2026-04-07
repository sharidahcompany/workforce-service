<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'notes' => $this->notes,

            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),

            'sprints' => SprintResource::collection(
                $this->whenLoaded('sprints')
            ),

            'team' => UserResource::collection(
                $this->whenLoaded('users')
            ),

            'files' => $this->whenLoaded('media', function () {
                return $this->media
                    ->where('collection_name', 'project_files')
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

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
