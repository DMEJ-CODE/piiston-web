<?php

use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageEmployee;
use App\Models\Globalization\Address;
use App\Models\Globalization\City;
use App\Models\Globalization\Country;
use App\Models\Globalization\Currency;
use App\Models\Globalization\Region;
use App\Models\Marketplace\Order;
use App\Models\Marketplace\PartBrand;
use App\Models\Marketplace\PartCategory;
use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\SellerProfile;
use App\Models\Marketplace\SparePart;
use App\Models\User;

function marketplaceOrderFixtures(): array
{
    $currency = Currency::create([
        'name' => 'CFA Franc',
        'code' => 'XAF',
        'symbol' => 'FCFA',
        'status' => 'active',
    ]);

    $country = Country::create([
        'name' => 'Cameroon',
        'iso_code' => 'CM',
        'phone_code' => '+237',
        'currency_id' => $currency->id,
        'status' => 'active',
    ]);
    $region = Region::create(['country_id' => $country->id, 'name' => 'Littoral']);
    $city = City::create(['region_id' => $region->id, 'name' => 'Douala']);
    $address = Address::create([
        'country_id' => $country->id,
        'region_id' => $region->id,
        'city_id' => $city->id,
        'street' => 'Rue des garages',
    ]);

    $owner = User::factory()->create(['country_id' => $country->id]);
    $mechanic = User::factory()->create(['country_id' => $country->id]);
    $sellerUser = User::factory()->create(['country_id' => $country->id]);
    $company = GarageCompany::create([
        'owner_id' => $owner->id,
        'country_id' => $country->id,
        'name' => 'Garage Central',
    ]);
    $branch = GarageBranch::create(['company_id' => $company->id, 'name' => 'Akwa']);
    GarageEmployee::create([
        'branch_id' => $branch->id,
        'user_id' => $mechanic->id,
        'position' => 'MECHANIC',
    ]);

    $seller = SellerProfile::create([
        'user_id' => $sellerUser->id,
        'country_id' => $country->id,
        'business_name' => 'Pièces Auto Douala',
        'business_type' => 'INDIVIDUAL',
        'registration_number' => 'PAD001',
        'status' => 'active',
    ]);
    $category = PartCategory::create(['name' => 'Freinage']);
    $brand = PartBrand::create(['name' => 'Bosch']);
    $part = SparePart::create([
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'name' => 'Plaquettes de frein',
        'part_number' => 'BP-100',
        'condition' => 'NEW',
        'quality_grade' => 'A',
    ]);
    $listing = ProductListing::create([
        'seller_id' => $seller->id,
        'part_id' => $part->id,
        'price' => 15000,
        'currency_id' => $currency->id,
        'quantity' => 10,
        'condition' => 'NEW',
        'availability' => 'INSTOCK',
        'status' => true,
    ]);

    return compact('address', 'branch', 'currency', 'listing', 'mechanic', 'owner', 'seller');
}

test('a mechanic can buy a part for their garage branch and the owner can view it', function () {
    $fixtures = marketplaceOrderFixtures();

    $this->actingAs($fixtures['mechanic'], 'sanctum')
        ->postJson('/api/marketplace/orders', [
            'seller_id' => $fixtures['seller']->id,
            'address_id' => $fixtures['address']->id,
            'garage_branch_id' => $fixtures['branch']->id,
            'items' => [
                ['listing_id' => $fixtures['listing']->id, 'quantity' => 2],
            ],
            'total_amount' => 1,
            'currency_id' => $fixtures['currency']->id,
        ])
        ->assertCreated()
        ->assertJsonPath('order.garage_branch_id', $fixtures['branch']->id)
        ->assertJsonPath('order.items.0.quantity', 2);

    $order = Order::whereBelongsTo($fixtures['branch'], 'garageBranch')->firstOrFail();
    $this->assertModelExists($order);
    expect((float) $order->total_amount)->toBe(30000.0);

    $this->actingAs($fixtures['mechanic'], 'sanctum')
        ->getJson("/api/garage/branches/{$fixtures['branch']->id}/marketplace-orders")
        ->assertForbidden();

    $this->actingAs($fixtures['owner'], 'sanctum')
        ->getJson("/api/garage/branches/{$fixtures['branch']->id}/marketplace-orders")
        ->assertSuccessful()
        ->assertJsonPath('data.0.garage_branch_id', $fixtures['branch']->id)
        ->assertJsonPath('data.0.buyer.id', $fixtures['mechanic']->id);
});

test('a user who does not work for the garage cannot assign an order to its branch', function () {
    $fixtures = marketplaceOrderFixtures();
    $outsider = User::factory()->create();

    $this->actingAs($outsider, 'sanctum')
        ->postJson('/api/marketplace/orders', [
            'seller_id' => $fixtures['seller']->id,
            'address_id' => $fixtures['address']->id,
            'garage_branch_id' => $fixtures['branch']->id,
            'items' => [
                ['listing_id' => $fixtures['listing']->id, 'quantity' => 1],
            ],
            'total_amount' => 15000,
            'currency_id' => $fixtures['currency']->id,
        ])
        ->assertForbidden();
});
