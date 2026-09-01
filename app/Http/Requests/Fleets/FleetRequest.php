<?php

namespace App\Http\Requests\Fleets;

use Illuminate\Foundation\Http\FormRequest;

class FleetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',
            'type' => 'required|string',
        ];
    }
}
