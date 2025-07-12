<?php

namespace Database\Factories;

use App\Models\GoogleConnection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoogleConnection>
 */
class GoogleConnectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GoogleConnection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'access_token' => $this->faker->uuid(),
            'refresh_token' => $this->faker->uuid(),
            'token_expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'google_user_id' => $this->faker->numerify('##########'),
            'email' => $this->faker->email(),
            'name' => $this->faker->name(),
            'picture' => $this->faker->imageUrl(100, 100, 'people'),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the connection is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'token_expires_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the connection is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
} 