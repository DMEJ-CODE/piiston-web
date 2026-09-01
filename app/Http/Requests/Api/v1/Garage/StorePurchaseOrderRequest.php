<?php

namespace App\Http\Requests\Api\v1\Garage;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:garage_suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.part_name' => ['required', 'string', 'max:255'],
            'items.*.part_number' => ['nullable', 'string', 'max:100'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
