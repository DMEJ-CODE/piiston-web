<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Globalization\Country;
use App\Models\Globalization\Region;

class GlobalizationController extends Controller
{
    public function countries()
    {
        return response()->json(Country::where('status', true)->with(['currency', 'timezone'])->get());
    }

    public function countryDetails($isoCode)
    {
        $country = Country::where('iso_code', strtoupper($isoCode))
            ->with([
                'currency',
                'languages',
                'currencies',
                'timezone',
                'configurations',
                'phoneCode',
                'taxes',
                'paymentProviders',
                'documentTypes',
            ])
            ->firstOrFail();

        return response()->json($country);
    }

    public function regions(Country $country)
    {
        return response()->json($country->regions()->where('status', true)->get());
    }

    public function cities(Region $region)
    {
        return response()->json($region->cities()->where('status', true)->get());
    }
}
