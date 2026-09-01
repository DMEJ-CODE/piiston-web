<?php

namespace App\Http\Resources\Garages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchNearbyResource extends JsonResource
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
            'distance' => round($this->distance, 1),
            'address' => [
                'id' => $this->address->id ?? null,
                'full_address' => $this->address->full_address ?? null,
            ],
            'company' => [
                'id' => $this->company->id ?? null,
                'name' => $this->company->name ?? null,
                'owner_id' => $this->company->owner_id ?? null,
                'rating' => $this->company->rating ?? 4.5,
            ],
            'status' => $this->status,
        ];
    }
}
