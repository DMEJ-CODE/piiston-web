<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Globalization\Currency;
use App\Models\Marketplace\PartBrand;
use App\Models\Marketplace\PartCategory;
use App\Models\Marketplace\ProductListing;
use App\Models\Marketplace\SellerProfile;
use App\Models\Marketplace\SparePart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $country;

    protected $currency;

    protected $category;

    protected $brand;

    protected $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->country = Country::create([
            'name' => 'Cameroon',
            'iso_code' => 'CM',
            'phone_code' => '+237',
            'status' => 'active',
        ]);

        $this->currency = Currency::create(['name' => 'CFA Franc', 'code' => 'XAF', 'symbol' => 'FCFA', 'status' => 'active']);

        $this->user = User::create([
            'first_name' => 'Sarah',
            'last_name' => 'Seller',
            'email' => 'sarah@marketplace.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
            'country_id' => $this->country->id,
            'status' => 'active',
        ]);

        $this->seller = SellerProfile::create([
            'user_id' => $this->user->id,
            'country_id' => $this->country->id,
            'business_name' => 'Sarah Parts Shop',
            'business_type' => 'INDIVIDUAL',
            'registration_number' => 'SARAH001',
            'status' => 'active',
        ]);

        $this->category = PartCategory::create(['name' => 'Braking System']);
        $this->brand = PartBrand::create(['name' => 'Bosch']);
    }

    public function test_public_can_search_products()
    {
        $part = SparePart::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Bosch Brake Pads',
            'part_number' => 'B-12345',
            'condition' => 'NEW',
            'quality_grade' => 'A',
        ]);

        ProductListing::create([
            'seller_id' => $this->seller->id,
            'part_id' => $part->id,
            'price' => 15000,
            'currency_id' => $this->currency->id,
            'quantity' => 10,
            'condition' => 'NEW',
            'availability' => 'INSTOCK',
            'status' => true,
        ]);

        $response = $this->getJson('/api/marketplace/products?q=Bosch');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Bosch Brake Pads']);
    }

    public function test_user_can_add_to_cart()
    {
        $part = SparePart::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Bosch Brake Pads',
            'part_number' => 'B-12345',
            'condition' => 'NEW',
            'quality_grade' => 'A',
        ]);

        $listing = ProductListing::create([
            'seller_id' => $this->seller->id,
            'part_id' => $part->id,
            'price' => 15000,
            'currency_id' => $this->currency->id,
            'quantity' => 10,
            'condition' => 'NEW',
            'availability' => 'INSTOCK',
            'status' => true,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/marketplace/cart/add', [
                'listing_id' => $listing->id,
                'quantity' => 2,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', ['listing_id' => $listing->id, 'quantity' => 2]);
    }
}
