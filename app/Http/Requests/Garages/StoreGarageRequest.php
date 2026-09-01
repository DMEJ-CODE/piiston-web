<?php

namespace App\Http\Requests\Garages;

use Illuminate\Foundation\Http\FormRequest;

class StoreGarageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100|unique:garage_companies',
            'email' => 'required|email|max:255|unique:garage_companies',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'description' => 'nullable|string',
        ];
    }
}
