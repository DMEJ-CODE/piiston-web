<?php

namespace App\Http\Requests\Vehicles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<Unique|string>|string>
     */
    public function rules(): array
    {
        $vehicleId = $this->route('vehicle');

        return [
            'brand_id' => 'sometimes|required|exists:vehicle_brands,id',
            'model_id' => 'sometimes|required|exists:vehicle_models,id',
            'year' => 'sometimes|required|integer|min:1900|max:'.(date('Y') + 1),
            'vin' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
                Rule::unique('vehicles', 'vin')->ignore($vehicleId),
            ],
            'license_plate' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'license_plate')->ignore($vehicleId),
            ],
            'country_id' => 'sometimes|required|exists:countries,id',
            'mileage' => 'sometimes|required|integer|min:0',
            'color' => 'sometimes|nullable|string|max:30',
            'fuel_type_id' => 'sometimes|nullable|exists:fuel_types,id',
            'transmission_id' => 'sometimes|nullable|exists:transmissions,id',
            'photo' => 'sometimes|nullable|image|max:5120',
        ];
    }
}
