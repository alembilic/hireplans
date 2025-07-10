<?php

namespace Database\Factories;

use App\Helpers\HelperFunc;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();
        $slug = Str::slug($title);

        // Ensure the slug is unique by appending a unique identifier
        if (Job::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::random(8);
        }

        return [
            'job_ref' => fake()->unique()->numerify('J-########'),
            'employer_id' => Employer::factory(),
            'title' => $title,
            'slug' => $slug, //fake()->unique()->slug,
            'details' => fake()->paragraph(),
            'location' => fake()->city() .', '. fake()->country(),
            'salary' => fake()->optional()->regexify('\$\d{2,3},\d{3} - \$\d{2,3},\d{3}'),
            'job_type' => fake()->randomElement(array_keys(HelperFunc::getJobTypes())),
            'category' => fake()->randomElement(array_keys(HelperFunc::getJobCategories())),
            'experience_level' => fake()->randomElement(array_keys(HelperFunc::getExperienceLevels())),
            'application_deadline' => fake()->optional()->date(),
            'is_active' => fake()->boolean(80) ? 1 : 0, // 80% chance of being active
            'created_by' => User::factory(),
        ];
    }
}
