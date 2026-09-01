<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'invoice_number' => $this->invoice_number,
            'total' => $this->total,
            'currency' => $this->currency->code ?? null,
            'status' => $this->status,
            'issued_at' => $this->issued_at,
            'due_date' => $this->due_date,
            'items' => $this->items->map(function ($item) {
                return [
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                ];
            }),
        ];
    }
}
