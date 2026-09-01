<?php

namespace Database\Seeders;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentCategory;
use App\Models\Documents\DocumentType;
use App\Models\Documents\MediaFile;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageDepartment;
use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use App\Models\Globalization\Currency;
use App\Models\Globalization\Region;
use App\Models\Identity\Role;
use App\Models\Marketplace\PartBrand;
use App\Models\Marketplace\PartCategory;
use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\SellerProfile;
use App\Models\Marketplace\SparePart;
use App\Models\Social\SocialInteraction;
use App\Models\User;
use App\Models\Vehicles\FuelType;
use App\Models\Vehicles\Transmission;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Prerequisites
        $cameroon = Country::where('iso_code', 'CM')->first();
        if (! $cameroon) {
            $this->command->error('Cameroon country not found. Please run GlobalizationSeeder first.');

            return;
        }

        $xaf = Currency::where('code', 'XAF')->first();
        $cityYde = City::where('name', 'Yaoundé')->first();
        $cityDla = City::where('name', 'Douala')->first();
        $regionCe = Region::where('code', 'CE')->first();
        $regionLt = Region::where('code', 'LT')->first();

        $ownerRole = Role::where('name', 'VEHICLE_OWNER')->first();
        $sellerRole = Role::where('name', 'SPARE_PART_SELLER')->first();
        $garageRole = Role::where('name', 'GARAGE_OWNER')->first();

        // 2. Seed Vehicle Owners & Vehicles (4 owners, 4 vehicles each)
        for ($i = 1; $i <= 4; $i++) {
            $owner = User::updateOrCreate(
                ['email' => "owner$i@example.com"],
                [
                    'first_name' => 'Owner',
                    'last_name' => "$i",
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'country_id' => $cameroon->id,
                ]
            );
            $owner->roles()->syncWithoutDetaching([$ownerRole->id]);

            // Add 4 vehicles for each owner
            $brands = ['Toyota', 'Mercedes-Benz', 'Tesla', 'BMW'];
            foreach ($brands as $index => $brandName) {
                $brand = VehicleBrand::firstOrCreate(['name' => $brandName]);
                $modelName = 'Model '.($index + 1);
                $model = VehicleModel::firstOrCreate(['brand_id' => $brand->id, 'name' => $modelName]);

                $vin = 'VINOWNER'.$i.'B'.$index.Str::random(5);
                Vehicle::updateOrCreate(
                    ['vin' => $vin],
                    [
                        'owner_id' => $owner->id,
                        'country_id' => $cameroon->id,
                        'brand_id' => $brand->id,
                        'model_id' => $model->id,
                        'year' => 2015 + $index,
                        'license_plate' => 'LT-'.rand(100, 999).'-'.chr(rand(65, 90)).chr(rand(65, 90)),
                        'color' => ['White', 'Black', 'Grey', 'Blue'][$index],
                        'fuel_type_id' => FuelType::inRandomOrder()->first()?->id ?? 1,
                        'transmission_id' => Transmission::inRandomOrder()->first()?->id ?? 1,
                        'mileage' => rand(10000, 200000),
                        'status' => 'active',
                    ]
                );
            }
        }

        // 3. Seed Part Sellers & Products (5 sellers, products with videos)
        $docCategory = DocumentCategory::where('name', 'Vehicle')->first();
        $docType = DocumentType::where('name', 'Registration Certificate')->first();

        for ($i = 1; $i <= 5; $i++) {
            $sellerUser = User::updateOrCreate(
                ['email' => "seller$i@example.com"],
                [
                    'first_name' => 'Seller',
                    'last_name' => "$i",
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'country_id' => $cameroon->id,
                ]
            );
            $sellerUser->roles()->syncWithoutDetaching([$sellerRole->id]);

            $sellerProfile = SellerProfile::updateOrCreate(
                ['user_id' => $sellerUser->id],
                [
                    'country_id' => $cameroon->id,
                    'business_name' => "Auto Parts Pro $i",
                    'business_type' => 'PART_STORE',
                    'verification_status' => 'verified',
                    'rating' => 4.0 + ($i * 0.2),
                ]
            );

            // Seed 10 products per seller
            for ($j = 1; $j <= 10; $j++) {
                $category = PartCategory::inRandomOrder()->first();
                $brand = PartBrand::inRandomOrder()->first();
                $partNumber = 'PN-S'.$i.'-P'.$j;

                $part = SparePart::updateOrCreate(
                    ['part_number' => $partNumber],
                    [
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'name' => 'Premium '.$category->name.' '.Str::random(5),
                        'condition' => 'NEW',
                        'quality_grade' => 'OEM',
                    ]
                );

                $listing = ProductListing::updateOrCreate(
                    ['seller_id' => $sellerProfile->id, 'part_id' => $part->id],
                    [
                        'price' => rand(5000, 50000),
                        'currency_id' => $xaf->id,
                        'quantity' => rand(5, 50),
                        'condition' => 'NEW',
                        'availability' => 'IN_STOCK',
                        'status' => true,
                    ]
                );

                // Attach a "TikTok-style" video (simulated with a Document and MediaFile)
                $doc = Document::updateOrCreate(
                    ['owner_type' => ProductListing::class, 'owner_id' => $listing->id, 'title' => "Product Demo $j"],
                    [
                        'category_id' => $docCategory->id,
                        'type_id' => $docType->id,
                        'file_name' => "demo_$j.mp4",
                        'file_path' => 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4',
                        'mime_type' => 'video/mp4',
                        'file_size' => 1024 * 1024,
                        'visibility' => 'public',
                        'created_by' => $sellerUser->id,
                        'status' => 'active',
                    ]
                );

                MediaFile::updateOrCreate(
                    ['document_id' => $doc->id],
                    [
                        'media_type' => 'VIDEO',
                        'thumbnail' => "https://picsum.photos/seed/product$j/400/600",
                        'duration_seconds' => 15,
                        'resolution' => '720p',
                    ]
                );

                // Add some social interactions
                SocialInteraction::updateOrCreate(
                    ['user_id' => $ownerRole->users()->first()?->id ?? 1, 'interactable_id' => $listing->id, 'interactable_type' => ProductListing::class, 'type' => 'LIKE']
                );
            }
        }

        // 4. Seed Garages (4 garages)
        for ($i = 1; $i <= 4; $i++) {
            $garageOwner = User::updateOrCreate(
                ['email' => "garage$i@example.com"],
                [
                    'first_name' => 'Garage',
                    'last_name' => "Owner $i",
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'country_id' => $cameroon->id,
                ]
            );
            $garageOwner->roles()->syncWithoutDetaching([$garageRole->id]);

            $garage = GarageCompany::updateOrCreate(
                ['owner_id' => $garageOwner->id],
                [
                    'country_id' => $cameroon->id,
                    'name' => "Elite Garage $i",
                    'email' => "contact@elitegarage$i.cm",
                    'phone' => "+237 600 000 00$i",
                    'verification_status' => 'verified',
                ]
            );

            $branch = GarageBranch::updateOrCreate(
                ['company_id' => $garage->id, 'name' => ($i % 2 == 0) ? 'Douala Central' : 'Yaoundé Central'],
                [
                    'email' => "branch$i@elitegarage.cm",
                    'phone' => "+237 670 000 00$i",
                ]
            );

            GarageDepartment::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Mechanical']);
            GarageDepartment::firstOrCreate(['branch_id' => $branch->id, 'name' => 'Bodywork']);
        }

        $this->command->info('Demo Seeder finished successfully!');
    }
}
