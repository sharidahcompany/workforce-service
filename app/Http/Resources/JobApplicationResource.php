<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'recommended_by' => $this->recommended_by,
            'recommended' => $this->whenLoaded('recommended', function () {
                return[
                    'id'=>$this->recommended->id,
                    'full_name'=>$this->recommended->full_name,
                    'avatar'=>$this->recommended->getFirstMediaUrl('avatar'),
                ];
             }),


            'career_post_id' => $this->career_post_id,
            

            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return[
                    'id'=>$this->user->id,
                    'full_name'=>$this->user->full_name,
                    'avatar'=>$this->user->getFirstMediaUrl('avatar'),
                ];
             }),

             'accepted_by'=>$this->accepted_by,   
             'accepted' => $this->whenLoaded('acceptedBy', function () {
                return[
                    'id'=>$this->acceptedBy->id,
                    'full_name'=>$this->acceptedBy->full_name,
                    'avatar'=>$this->acceptedBy->getFirstMediaUrl('avatar'),
                ];
             }),
             
             'confirmed_by'=>$this->confirmed_by,   
             'confirmed' => $this->whenLoaded('confirmedBy', function () {
                return[
                    'id'=>$this->confirmed->id,
                    'full_name'=>$this->confirmed->full_name,
                    'avatar'=>$this->confirmed->getFirstMediaUrl('avatar'),
                ];
            }),
             
            'status' => $this->status,
            'career_post' => new CareerPostResource($this->whenLoaded('careerPost')),
            'cv' => $this->getFirstMediaUrl('cv'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
