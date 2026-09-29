<?php

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            // Clients send the same `languages.code` values the web app stores,
            // so a single canonical code is accepted everywhere.
            'language' => [
                'required',
                'string',
                'size:2',
                Rule::exists('languages', 'code')->where('status', true),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'language.exists' => 'The selected language is not available.',
        ];
    }
}
