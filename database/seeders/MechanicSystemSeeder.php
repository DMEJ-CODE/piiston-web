<?php

namespace Database\Seeders;

use App\Models\Globalization\Country;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Mechanics\MechanicSkill;
use App\Models\Mechanics\MechanicType;
use App\Models\User;
use Illuminate\Database\Seeder;

class MechanicSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mechanic Types
        $types = [
            ['name' => 'INDEPENDENT', 'description' => 'Freelance mechanic working directly with clients.'],
            ['name' => 'GARAGE_EMPLOYEE', 'description' => 'Professional working in a registered garage.'],
            ['name' => 'MOBILE_MECHANIC', 'description' => 'Mechanic providing on-site repair services.'],
        ];
        foreach ($types as $t) {
            MechanicType::firstOrCreate(['name' => $t['name']], $t);
        }

        // 2. Technical Skills
        $skills = [
            ['name' => 'Engine Diagnosis', 'category' => 'Diagnostic'],
            ['name' => 'Brake System', 'category' => 'Safety'],
            ['name' => 'Suspension', 'category' => 'Mechanical'],
            ['name' => 'Electrical Repair', 'category' => 'Electrical'],
            ['name' => 'Hybrid System', 'category' => 'Specialized'],
        ];
        foreach ($skills as $s) {
            MechanicSkill::firstOrCreate(['name' => $s['name']], $s);
        }

        // 3. Setup Dave Mechanic Profile
        $dave = User::where('email', 'mechanic@piiston.com')->first();
        $cameroon = Country::where('iso_code', 'CM')->first();
        $typeEmp = MechanicType::where('name', 'GARAGE_EMPLOYEE')->first();

        if ($dave && $cameroon) {
            $profile = MechanicProfile::updateOrCreate(
                ['user_id' => $dave->id],
                [
                    'country_id' => $cameroon->id,
                    'type_id' => $typeEmp->id,
                    'professional_title' => 'Senior Diagnostic Technician',
                    'bio' => 'Specialist in engine diagnostics and hybrid vehicle systems with 10+ years of experience.',
                    'years_of_experience' => 12,
                    'rating' => 4.9,
                    'total_reviews' => 124,
                    'verification_status' => 'verified',
                ]
            );

            // Assign Skills
            $skillIds = MechanicSkill::whereIn('name', ['Engine Diagnosis', 'Brake System', 'Electrical Repair'])->pluck('id');
            $profile->skills()->syncWithPivotValues($skillIds, ['experience_level' => 'expert', 'years_practiced' => 8]);

            // Add Location
            $profile->location()->updateOrCreate(
                ['mechanic_id' => $profile->id],
                [
                    'latitude' => 3.8480,
                    'longitude' => 11.5021,
                    'radius_km' => 15,
                    'last_updated' => now(),
                ]
            );
        }
    }
}
