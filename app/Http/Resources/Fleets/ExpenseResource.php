<?php

namespace App\Http\Resources\Fleets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fleet_id' => $this->fleet_id,
            'vehicle_id' => $this->vehicle_id,
            'expense_type' => $this->expense_type,
            'amount' => $this->amount,
            'currency' => $this->currency->code ?? null,
            'expense_date' => $this->expense_date,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
