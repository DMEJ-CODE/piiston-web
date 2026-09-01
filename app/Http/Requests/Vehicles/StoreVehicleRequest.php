<?php

namespace App\Http\Requests\Vehicles;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'vin' => 'nullable|string|max:50|unique:vehicles',
            'license_plate' => 'required|string|max:20|unique:vehicles',
            'country_id' => 'required|exists:countries,id',
            'mileage' => 'required|integer|min:0',
            'color' => 'nullable|string|max:30',
            'fuel_type_id' => 'nullable|exists:fuel_types,id',
            'transmission_id' => 'nullable|exists:transmissions,id',
            'photo' => 'nullable|image|max:5120',
        ];
    }
}
