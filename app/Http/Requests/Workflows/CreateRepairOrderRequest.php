<?php

namespace App\Http\Requests\Workflows;

use Illuminate\Foundation\Http\FormRequest;

class CreateRepairOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'customer_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:garage_branches,id',
            'problem_description' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
        ];
    }
}
