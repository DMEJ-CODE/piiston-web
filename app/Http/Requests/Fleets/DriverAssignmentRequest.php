<?php

namespace App\Http\Requests\Fleets;

use Illuminate\Foundation\Http\FormRequest;

class DriverAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:DRIVER,CO-DRIVER',
        ];
    }
}
