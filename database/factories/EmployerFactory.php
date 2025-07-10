<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\EmployerStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employer>
 */
class EmployerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employer_ref' => fake()->unique()->numerify('E-########'),
            'name' => fake()->company(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'website' => fake()->url(),
            'logo' => fake()->imageUrl(),
            'details' => fake()->sentence(),
            'status' => EmployerStatus::IN_PROGRESS->value, // Default to In Progress
        ];
    }
}
