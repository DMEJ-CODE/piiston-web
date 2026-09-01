<?php

namespace App\Http\Resources\Marketplace;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'business_type' => $this->business_type,
            'description' => $this->description,
            'logo' => $this->logo,
            'rating' => $this->rating,
            'verification_status' => $this->verification_status,
            'status' => $this->status,
            'country' => $this->country->name ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
