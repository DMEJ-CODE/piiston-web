<?php

namespace App\Http\Resources\Finance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency->code ?? null,
            'reference' => $this->reference,
            'checkout_url' => $this->checkout_url,
            'status' => $this->status,
            'type' => $this->transaction_type,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
        ];
    }
}
