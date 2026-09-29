<?php

namespace App\Http\Requests\Marketplace;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarketplaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'seller_id' => ['required', 'exists:seller_profiles,id'],
            'address_id' => ['required', 'exists:addresses,id'],
            'garage_branch_id' => ['nullable', 'exists:garage_branches,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.listing_id' => ['required', 'exists:product_listings,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'phone' => ['nullable', 'string'],
            'channel' => ['nullable', 'string'],
        ];
    }
}
