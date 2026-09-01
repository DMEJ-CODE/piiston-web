<?php

namespace App\Http\Requests\Fleets;

use Illuminate\Foundation\Http\FormRequest;

class VehicleAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
        ];
    }
}
