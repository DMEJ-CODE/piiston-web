<?php

namespace Tests\Feature\Api\v1;

use App\Models\AI\AiAssistant;
use App\Models\AI\AiModel;
use App\Models\AI\AiPromptTemplate;
use App\Models\AI\AiProvider;
use App\Models\Globalization\Country;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use App\Services\AI\DiagnosisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $vehicle;

    protected $assistant;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create(['name' => 'Cameroon', 'iso_code' => 'CM', 'phone_code' => '+237', 'status' => 'active']);
        $this->user = User::create([
            'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'phone' => '12345',
            'password' => bcrypt('password'), 'country_id' => $country->id, 'status' => 'active',
        ]);

        $brand = VehicleBrand::create(['name' => 'Toyota']);
        $model = VehicleModel::create(['brand_id' => $brand->id, 'name' => 'Corolla']);
        $this->vehicle = Vehicle::create([
            'owner_id' => $this->user->id, 'brand_id' => $brand->id, 'model_id' => $model->id,
            'year' => 2020, 'license_plate' => 'ABC', 'country_id' => $country->id, 'mileage' => 1000,
        ]);

        $provider = AiProvider::create(['name' => 'OpenAI', 'provider_code' => 'OPENAI', 'status' => true]);
        $aiModel = AiModel::create(['provider_id' => $provider->id, 'model_name' => 'gpt-4o', 'purpose' => 'chat', 'status' => true]);

        $this->assistant = AiAssistant::create([
            'name' => 'Test Assistant',
            'assistant_type' => 'GENERAL',
            'default_model_id' => $aiModel->id,
            'status' => true,
        ]);

        AiPromptTemplate::create([
            'name' => 'auto_diagnosis_v1', 'purpose' => 'DIAGNOSIS',
            'system_prompt' => 'Symptoms: {symptoms}. Brand: {vehicle_brand}', 'status' => true,
        ]);
    }

    public function test_diagnosis_service_generates_record()
    {
        $this->actingAs($this->user);
        $service = app(DiagnosisService::class);

        $diagnosis = $service->diagnoseVehicle($this->vehicle, 'Engine rattling');

        $this->assertDatabaseHas('ai_diagnoses', [
            'vehicle_id' => $this->vehicle->id,
            'symptoms' => 'Engine rattling',
        ]);

        $this->assertDatabaseHas('ai_usage_logs', [
            'user_id' => $this->user->id,
        ]);
    }
}
