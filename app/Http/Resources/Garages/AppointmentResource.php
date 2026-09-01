<?php

namespace App\Http\Resources\Garages;

use App\Http\Resources\Vehicles\VehicleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'scheduled_date' => $this->scheduled_date,
            'duration_minutes' => $this->duration_minutes,
            'notes' => $this->notes,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'service' => new ServiceResource($this->whenLoaded('service')),
            'created_at' => $this->created_at,
        ];
    }
}
