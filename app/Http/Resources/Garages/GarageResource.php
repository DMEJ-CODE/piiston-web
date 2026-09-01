<?php

namespace App\Http\Resources\Garages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GarageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'logo' => $this->logo,
            'description' => $this->description,
            'rating' => $this->rating,
            'verification_status' => $this->verification_status,
            'status' => $this->status,
            'currency' => [
                'code' => $this->country->currency->code ?? 'XAF',
                'symbol' => $this->country->currency->symbol ?? 'FCFA',
            ],
            'branches' => BranchResource::collection($this->whenLoaded('branches')),
            'created_at' => $this->created_at,
        ];
    }
}
