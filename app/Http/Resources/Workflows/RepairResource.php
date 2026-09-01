<?php

namespace App\Http\Resources\Workflows;

use App\Http\Resources\Identity\UserResource;
use App\Http\Resources\Vehicles\VehicleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'priority' => $this->priority,
            'problem_description' => $this->problem_description,
            'estimated_cost' => $this->estimated_cost,
            'final_cost' => $this->final_cost,
            'opened_at' => $this->opened_at,
            'closed_at' => $this->closed_at,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'branch' => $this->whenLoaded('branch', [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
                'phone' => $this->branch->phone,
            ]),
            'customer' => new UserResource($this->whenLoaded('garageCustomer') ? $this->garageCustomer->user : null),
            'mechanic' => new UserResource($this->whenLoaded('mechanic')),
            'diagnosis' => $this->diagnosis ? [
                'findings' => $this->diagnosis->findings,
                'recommendations' => $this->diagnosis->recommendations,
                'mechanic_name' => $this->diagnosis->mechanic?->name,
            ] : null,
            'estimate' => new EstimateResource($this->whenLoaded('estimate')),
            'progress' => $this->whenLoaded('progress'),
            'created_at' => $this->created_at,
        ];
    }
}
