<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JobApplication;
use App\Models\Job;
use App\Models\Candidate;
// Assuming Attachment model exists for handling CVs and cover letters
use Orchid\Attachment\Models\Attachment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cvAttachmentId = null;
        $clAttachmentId = null;

        return [
            'application_ref' => fake()->unique()->bothify('A-########'),
            'job_id' => Job::factory(),
            'candidate_id' => Candidate::factory()->afterCreating(function ($candidate) use (&$cvAttachmentId, &$clAttachmentId) {
                // Assuming the Candidate model has a method to attach a CV
                $cvAttachment = Attachment::factory()->create([
                    'name' => 'CV',
                    'original_name' => 'fake_cv_'.fake()->numberBetween(1000, 2000).'.pdf',
                    'mime' => 'application/pdf',
                    'size' => fake()->numberBetween(10000, 50000),
                    'path' => 'path/to/cv.pdf',
                    'extension' => 'pdf',
                    'user_id' => $candidate->user_id, // Assuming the candidate has a user_id
                ]);

                // Attach the fake CV to the candidate
                $candidate->attachment()->attach($cvAttachment->id, ['field_name' => 'cv']);

                // Capture the CV attachment ID to use for the 'cv' field
                $cvAttachmentId = $cvAttachment->id;

                if (fake()->boolean(70)) {
                    // Assuming the Candidate model has a method to attach a cover letter
                    $clAttachment = Attachment::factory()->create([
                        'name' => 'Cover Letter',
                        'original_name' => 'fake_cover_letter_'.fake()->numberBetween(1000, 2000).'.pdf',
                        'mime' => 'application/pdf',
                        'size' => fake()->numberBetween(10000, 50000),
                        'path' => 'path/to/cover_letter.pdf',
                        'extension' => 'pdf',
                        'user_id' => $candidate->user_id, // Assuming the candidate has a user_id
                    ]);

                    // Attach the fake cover letter to the candidate
                    $candidate->attachment()->attach($clAttachment->id, ['field_name' => 'cover_letter']);

                    // Capture the cover letter attachment ID to use for the 'cover_letter' field
                    $clAttachmentId = $clAttachment->id;
                }
            }),
            'cv' => function () use (&$cvAttachmentId) {
                return $cvAttachmentId;
            },
            'cover_letter' => function () use (&$clAttachmentId) {
                return $clAttachmentId;
            },
            'notes' => fake()->optional()->sentences(3, true),
        ];
    }
}
