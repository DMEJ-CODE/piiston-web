<?php

namespace App\Http\Requests\Api\v1\Garage;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_id' => ['nullable', 'exists:garage_services,id'],
            'scheduled_date' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:15'],
        ];
    }
}
