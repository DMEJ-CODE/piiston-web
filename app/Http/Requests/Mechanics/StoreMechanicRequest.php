<?php

namespace App\Http\Requests\Mechanics;

use Illuminate\Foundation\Http\FormRequest;

class StoreMechanicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'professional_title' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'bio' => 'nullable|string',
            'type_id' => 'required|exists:mechanic_types,id',
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
