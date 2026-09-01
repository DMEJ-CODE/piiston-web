<?php

namespace App\Http\Resources\Garages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => [
                'id' => $this->address->id ?? null,
                'full_address' => $this->address?->full_address ?? null,
            ],
            'status' => $this->status,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'created_at' => $this->created_at,
        ];
    }
}
