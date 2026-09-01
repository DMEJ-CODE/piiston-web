<?php

namespace App\Http\Resources\Garages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairPartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'part_number' => $this->part_number,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'manufacturer' => $this->manufacturer,
            'compatible_vehicles' => $this->compatible_vehicles,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'stock_quantity' => $this->stock_quantity,
            'minimum_stock' => $this->minimum_stock,
            'maximum_stock' => $this->maximum_stock,
            'storage_location' => $this->storage_location,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
