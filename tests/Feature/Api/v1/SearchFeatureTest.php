<?php

namespace Tests\Feature\Api\v1;

use App\Models\Globalization\Country;
use App\Models\Search\SearchCategory;
use App\Models\Search\SearchIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create(['name' => 'Cameroon', 'iso_code' => 'CM', 'phone_code' => '+237', 'status' => 'active']);
        $this->user = User::create([
            'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'phone' => '12345',
            'password' => bcrypt('password'), 'country_id' => $country->id, 'status' => 'active',
        ]);

        $category = SearchCategory::create(['name' => 'Garage']);
        SearchIndex::create([
            'entity_type' => 'Garage', 'entity_id' => 1, 'category_id' => $category->id,
            'title' => 'Toyota Special Center', 'description' => 'Best Toyota garage',
            'keywords' => 'toyota center garage', 'status' => true,
        ]);
    }

    public function test_global_search_returns_indexed_results()
    {
        $response = $this->getJson('/api/search/global?q=Toyota');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Toyota Special Center']);
    }

    public function test_search_history_is_recorded_for_logged_in_user()
    {
        $this->actingAs($this->user);

        $this->getJson('/api/search/global?q=Toyota');

        $this->assertDatabaseHas('search_histories', [
            'user_id' => $this->user->id,
            'keyword' => 'Toyota',
        ]);
    }
}
