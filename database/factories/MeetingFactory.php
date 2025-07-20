<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\Job;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['video', 'phone']),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'duration_minutes' => $this->faker->randomElement([15, 30, 45, 60, 90]),
            'description' => $this->faker->optional()->paragraph(),
            'job_id' => Job::inRandomOrder()->first()?->id,
            'candidate_id' => Candidate::factory(),
            'created_by' => User::factory(),
            'meeting_link' => $this->faker->optional()->url(),
            'phone_number' => $this->faker->optional()->phoneNumber(),
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
        ];
    }

    /**
     * Indicate that the meeting is a video call.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'video',
            'meeting_link' => $this->faker->url(),
            'phone_number' => null,
        ]);
    }

    /**
     * Indicate that the meeting is a phone call.
     */
    public function phone(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'phone',
            'phone_number' => $this->faker->phoneNumber(),
            'meeting_link' => null,
        ]);
    }

    /**
     * Indicate that the meeting is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+2 weeks'),
        ]);
    }

    /**
     * Indicate that the meeting is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'scheduled_at' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
        ]);
    }

    /**
     * Indicate that the meeting is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
