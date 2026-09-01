<?php

namespace Database\Seeders;

use App\Models\Marketplace\SellerProfile;
use App\Models\Promotions\AdCreative;
use App\Models\Promotions\AdPlacement;
use App\Models\Promotions\Advertisement;
use App\Models\Promotions\Campaign;
use App\Models\Promotions\PromotionType;
use Illuminate\Database\Seeder;

class AdvertisingSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Standard Promotion Types
        $types = [
            ['name' => 'DISCOUNT', 'description' => 'Direct price reductions on parts or services.'],
            ['name' => 'FEATURED', 'description' => 'Top placement in search results.'],
            ['name' => 'ADVERTISEMENT', 'description' => 'Visual banners in the app.'],
            ['name' => 'COUPON', 'description' => 'Redeemable codes for specific offers.'],
        ];

        foreach ($types as $t) {
            PromotionType::firstOrCreate(['name' => $t['name']], $t);
        }

        // 2. Standard Ad Placements
        $placements = [
            ['name' => 'Home Banner', 'location' => 'HOME_TOP', 'device_target' => 'MOBILE', 'base_price' => 5000],
            ['name' => 'Marketplace Top', 'location' => 'MARKET_TOP', 'device_target' => 'MOBILE', 'base_price' => 3000],
        ];

        foreach ($placements as $p) {
            AdPlacement::firstOrCreate(['name' => $p['name']], $p);
        }

        // 3. Sample Campaign for Sarah Seller
        $seller = SellerProfile::first();
        if ($seller) {
            $campaign = Campaign::create([
                'owner_type' => 'Seller',
                'owner_id' => $seller->id,
                'name' => 'BOSCH Brake Pad Launch',
                'objective' => 'SALES',
                'budget' => 25000,
                'start_date' => now(),
                'status' => 'active',
            ]);

            $ad = Advertisement::create([
                'campaign_id' => $campaign->id,
                'title' => '20% Off Bosch Pads',
                'content' => 'Upgrade your braking safety today with premium Bosch pads.',
                'media_type' => 'IMAGE',
                'status' => 'active',
            ]);

            AdCreative::create([
                'advertisement_id' => $ad->id,
                'display_text' => 'Get 20% discount now!',
                'language_code' => 'en',
            ]);
        }
    }
}
