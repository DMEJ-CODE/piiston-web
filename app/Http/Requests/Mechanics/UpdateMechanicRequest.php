<?php

namespace App\Http\Requests\Mechanics;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMechanicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'professional_title' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'type_id' => 'nullable|exists:mechanic_types,id',
            'country_id' => 'nullable|exists:countries,id',
            'availability_status' => 'nullable|string|in:AVAILABLE,BUSY,OFFLINE',
            'profile_photo' => 'nullable|string',
        ];
    }
}
