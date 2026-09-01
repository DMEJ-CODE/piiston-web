<?php

namespace App\Http\Requests\Api\v1\Garage;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarrantyClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'repair_order_id' => ['required', 'exists:repair_orders,id'],
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'warranty_provider' => ['nullable', 'string', 'max:255'],
            'warranty_reference' => ['nullable', 'string', 'max:255'],
            'warranty_expiry_date' => ['nullable', 'date'],
            'claim_description' => ['required', 'string', 'max:2000'],
            'claim_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
