<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Finance\PaymentMethod;
use App\Models\Globalization\Country;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function methods()
    {
        $user = Auth::user();
        $countryId = $user->country_id ?? Country::where('iso_code', 'CM')->first()->id;

        return response()->json(
            PaymentMethod::where('country_id', $countryId)->where('status', true)->get()
        );
    }

    public function transactions()
    {
        return response()->json(
            Auth::user()->transactions()->with(['currency', 'paymentMethod'])->orderBy('created_at', 'desc')->paginate(20)
        );
    }
}
