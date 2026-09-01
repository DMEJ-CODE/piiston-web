<?php

namespace Database\Seeders;

use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use App\Models\Globalization\CountryConfiguration;
use App\Models\Globalization\Currency;
use App\Models\Globalization\DocumentType;
use App\Models\Globalization\Language;
use App\Models\Globalization\PaymentProvider;
use App\Models\Globalization\Region;
use App\Models\Globalization\TaxConfiguration;
use App\Models\Globalization\Timezone;
use Illuminate\Database\Seeder;

class GlobalizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Currencies
        $xaf = Currency::firstOrCreate(['code' => 'XAF'], ['name' => 'CFA Franc BEAC', 'symbol' => 'FCFA', 'decimal_places' => 0]);
        $ngn = Currency::firstOrCreate(['code' => 'NGN'], ['name' => 'Nigerian Naira', 'symbol' => '₦', 'decimal_places' => 2]);
        $kes = Currency::firstOrCreate(['code' => 'KES'], ['name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'decimal_places' => 2]);

        // 2. Languages
        $fr = Language::firstOrCreate(['code' => 'fr'], ['name' => 'French']);
        $en = Language::firstOrCreate(['code' => 'en'], ['name' => 'English']);
        $sw = Language::firstOrCreate(['code' => 'sw'], ['name' => 'Swahili']);

        // 3. Timezones
        $tzDouala = Timezone::firstOrCreate(['name' => 'Africa/Douala'], ['utc_offset' => '+01:00']);
        $tzLagos = Timezone::firstOrCreate(['name' => 'Africa/Lagos'], ['utc_offset' => '+01:00']);
        $tzNairobi = Timezone::firstOrCreate(['name' => 'Africa/Nairobi'], ['utc_offset' => '+03:00']);

        // 4. Countries
        $cameroon = Country::firstOrCreate(['iso_code' => 'CM'], [
            'name' => 'Cameroon',
            'phone_code' => '+237',
            'currency_id' => $xaf->id,
            'default_language_id' => $fr->id,
            'timezone_id' => $tzDouala->id,
            'status' => true,
        ]);
        $cameroon->languages()->sync([$fr->id => ['is_default' => true], $en->id => ['is_default' => false]]);

        $nigeria = Country::firstOrCreate(['iso_code' => 'NG'], [
            'name' => 'Nigeria',
            'phone_code' => '+234',
            'currency_id' => $ngn->id,
            'default_language_id' => $en->id,
            'timezone_id' => $tzLagos->id,
            'status' => true,
        ]);
        $nigeria->languages()->sync([$en->id => ['is_default' => true]]);

        $kenya = Country::firstOrCreate(['iso_code' => 'KE'], [
            'name' => 'Kenya',
            'phone_code' => '+254',
            'currency_id' => $kes->id,
            'default_language_id' => $en->id,
            'timezone_id' => $tzNairobi->id,
            'status' => true,
        ]);
        $kenya->languages()->sync([$en->id => ['is_default' => true], $sw->id => ['is_default' => false]]);

        // 5. Regions & Cities (Example for Cameroon)
        $centre = Region::firstOrCreate(['country_id' => $cameroon->id, 'code' => 'CE'], ['name' => 'Centre']);
        $littoral = Region::firstOrCreate(['country_id' => $cameroon->id, 'code' => 'LT'], ['name' => 'Littoral']);

        City::firstOrCreate(['region_id' => $centre->id, 'name' => 'Yaoundé'], ['latitude' => 3.8480, 'longitude' => 11.5021]);
        City::firstOrCreate(['region_id' => $littoral->id, 'name' => 'Douala'], ['latitude' => 4.0511, 'longitude' => 9.7679]);

        // 6. Configurations
        CountryConfiguration::firstOrCreate(['country_id' => $cameroon->id, 'key' => 'mobile_money_enabled'], ['value' => 'true', 'type' => 'boolean']);
        CountryConfiguration::firstOrCreate(['country_id' => $cameroon->id, 'key' => 'tax_enabled'], ['value' => 'true', 'type' => 'boolean']);

        // 7. Taxes
        TaxConfiguration::firstOrCreate(['country_id' => $cameroon->id, 'name' => 'VAT Cameroon'], ['percentage' => 19.25]);

        // 8. Payment Providers
        PaymentProvider::firstOrCreate(['country_id' => $cameroon->id, 'name' => 'Orange Money'], ['type' => 'mobile_money']);
        PaymentProvider::firstOrCreate(['country_id' => $cameroon->id, 'name' => 'MTN Mobile Money'], ['type' => 'mobile_money']);
        PaymentProvider::firstOrCreate(['country_id' => $nigeria->id, 'name' => 'Paystack'], ['type' => 'card']);

        // 9. Document Types
        DocumentType::firstOrCreate(['country_id' => $cameroon->id, 'name' => 'CNI'], ['required_for' => 'user']);
        DocumentType::firstOrCreate(['country_id' => $cameroon->id, 'name' => 'RCCM'], ['required_for' => 'garage']);
    }
}
