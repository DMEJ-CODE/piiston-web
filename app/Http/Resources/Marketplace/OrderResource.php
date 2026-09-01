<?php

namespace App\Http\Resources\Marketplace;

use App\Http\Resources\Identity\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency->code ?? null,
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
            'buyer' => new UserResource($this->whenLoaded('buyer')),
            'seller' => [
                'id' => $this->seller->id ?? null,
                'name' => $this->seller->business_name ?? null,
            ],
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->listing->part->name ?? 'Unknown',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}
