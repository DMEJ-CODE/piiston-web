<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_token' => 'required|string|max:500',
            'device_type' => 'required|string|in:android,ios,web',
            'platform' => 'required|string',
        ];
    }
}
