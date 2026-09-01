<?php

namespace Database\Factories;

use App\Models\Administration\Administrator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Administrator>
 */
class AdministratorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_number' => fake()->unique()->bothify('EMP-####'),
            'position' => fake()->jobTitle(),
            'status' => 'active',
        ];
    }
}
