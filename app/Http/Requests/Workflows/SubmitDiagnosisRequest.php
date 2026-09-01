<?php

namespace App\Http\Requests\Workflows;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDiagnosisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'symptoms' => 'required|string',
            'detected_problem' => 'required|string',
            'root_cause' => 'nullable|string',
            'solution' => 'required|string',
            'recommendation' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
        ];
    }
}
