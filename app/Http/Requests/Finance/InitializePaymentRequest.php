<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class InitializePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:100',
            'currency_id' => 'required|exists:currencies,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'transaction_type' => 'required|string|in:REPAIR,MARKETPLACE,SUBSCRIPTION,WALLET_FUNDING',
        ];
    }
}
