<?php

namespace App\Http\Resources\Vehicles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => [
                'id' => $this->brand->id ?? null,
                'name' => $this->brand->name ?? null,
                'logo' => $this->brand->logo ?? null,
            ],
            'model' => [
                'id' => $this->model->id ?? null,
                'name' => $this->model->name ?? null,
            ],
            'year' => $this->year,
            'vin' => $this->vin,
            'license_plate' => $this->license_plate,
            'mileage' => $this->mileage,
            'color' => $this->color,
            'status' => $this->status,
            'owner_id' => $this->owner_id,
            'country' => $this->country->name ?? null,
            'health' => $this->health ? [
                'health_score' => $this->health->health_score,
                'risk_level' => $this->health->risk_level,
            ] : null,
            'images' => $this->images->map(fn ($img) => [
                'url' => $img->image_url,
                'type' => $img->type,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
