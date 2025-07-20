<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Candidate;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
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
            'candidate_ref' => fake()->unique()->numerify('C-########'),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'current_company' => fake()->company(),
            'current_job_title' => fake()->jobTitle(),
            'languages' => implode(', ', fake()->words(3)),
            'skills' => implode(', ', fake()->words(5)),
            'notes' => fake()->paragraph(),
        ];
    }
}
