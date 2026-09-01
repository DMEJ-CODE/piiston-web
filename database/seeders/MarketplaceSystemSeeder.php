<?php

namespace Database\Seeders;

use App\Models\Globalization\Country;
use App\Models\Marketplace\PartBrand;
use App\Models\Marketplace\PartCategory;
use App\Models\Marketplace\PartVehicleCompatibility;
use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\SellerProfile;
use App\Models\Marketplace\SparePart;
use App\Models\User;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Database\Seeder;

class MarketplaceSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $engineCat = PartCategory::firstOrCreate(['name' => 'Engine'], ['description' => 'Internal combustion engine parts.']);
        $brakesCat = PartCategory::firstOrCreate(['name' => 'Brake System'], ['description' => 'Braking components.']);

        $oilFilterCat = PartCategory::firstOrCreate(['name' => 'Oil Filter'], ['parent_id' => $engineCat->id]);
        $brakePadCat = PartCategory::firstOrCreate(['name' => 'Brake Pad'], ['parent_id' => $brakesCat->id]);

        // 2. Brands
        $bosch = PartBrand::firstOrCreate(['name' => 'Bosch'], ['country_origin' => 'Germany']);
        $valeo = PartBrand::firstOrCreate(['name' => 'Valeo'], ['country_origin' => 'France']);

        // 3. Spare Parts
        $pad = SparePart::firstOrCreate(
            ['part_number' => 'BOSCH-BP-123'],
            [
                'category_id' => $brakePadCat->id,
                'brand_id' => $bosch->id,
                'name' => 'Premium Brake Pad Set - Front',
                'condition' => 'NEW',
                'quality_grade' => 'OEM',
            ]
        );

        $filter = SparePart::firstOrCreate(
            ['part_number' => 'VALEO-OF-456'],
            [
                'category_id' => $oilFilterCat->id,
                'brand_id' => $valeo->id,
                'name' => 'High-Efficiency Oil Filter',
                'condition' => 'NEW',
                'quality_grade' => 'ORIGINAL',
            ]
        );

        // 4. Compatibility
        $toyota = VehicleBrand::where('name', 'Toyota')->first();
        $corolla = VehicleModel::where('name', 'Corolla')->first();

        if ($pad && $corolla) {
            PartVehicleCompatibility::create([
                'part_id' => $pad->id,
                'vehicle_brand_id' => $toyota->id,
                'vehicle_model_id' => $corolla->id,
                'year_from' => 2000,
                'year_to' => 2006,
                'notes' => 'Fits all E120 Corolla models.',
            ]);
        }

        // 5. Seller Profile for Sarah Seller
        $sarah = User::where('email', 'seller@piiston.com')->first();
        $cameroon = Country::where('iso_code', 'CM')->first();

        if ($sarah && $cameroon) {
            $seller = SellerProfile::create([
                'user_id' => $sarah->id,
                'country_id' => $cameroon->id,
                'business_name' => 'Sarah Spare Parts Ltd',
                'business_type' => 'PART_STORE',
                'verification_status' => 'verified',
                'rating' => 4.8,
            ]);

            // 6. Product Listings
            ProductListing::create([
                'seller_id' => $seller->id,
                'part_id' => $pad->id,
                'price' => 15000,
                'currency_id' => 1, // XAF
                'quantity' => 10,
                'condition' => 'NEW',
                'availability' => 'IN_STOCK',
            ]);

            ProductListing::create([
                'seller_id' => $seller->id,
                'part_id' => $filter->id,
                'price' => 5500,
                'currency_id' => 1, // XAF
                'quantity' => 25,
                'condition' => 'NEW',
                'availability' => 'IN_STOCK',
            ]);
        }
    }
}
