<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        \Log::info('Registering attempt. Data: '.json_encode($this->all()));
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'country_id' => 'required|integer',
            'role' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'The selected role (:input) is invalid. Allowed: VEHICLE_OWNER, MECHANIC, SPARE_PART_SELLER...',
        ];
    }
}
