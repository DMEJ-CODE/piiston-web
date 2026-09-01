<?php

namespace App\Http\Resources\Mechanics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MechanicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name ?? null,
            'professional_title' => $this->professional_title,
            'bio' => $this->bio,
            'years_of_experience' => $this->years_of_experience,
            'rating' => $this->rating,
            'total_reviews' => $this->total_reviews,
            'verification_status' => $this->verification_status,
            'type' => $this->type->name ?? null,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'certifications' => CertificationResource::collection($this->whenLoaded('certifications')),
            'created_at' => $this->created_at,
        ];
    }
}
