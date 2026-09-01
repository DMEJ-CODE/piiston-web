<?php

namespace App\Http\Requests\Fleets;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'expense_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ];
    }
}
