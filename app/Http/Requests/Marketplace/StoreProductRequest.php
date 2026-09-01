<?php

namespace App\Http\Requests\Marketplace;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'part_id' => 'required|exists:spare_parts,id',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|in:NEW,USED,REFURBISHED',
            'availability' => 'required|string',
        ];
    }
}
