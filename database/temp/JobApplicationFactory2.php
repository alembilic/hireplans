<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JobApplication;
use App\Models\Job;
use App\Models\Candidate;
// Assuming Attachment model exists for handling CVs and cover letters
use Orchid\Attachment\Models\Attachment;
use Illuminate\Support\Facades\Storage;

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

        return [
            'application_ref' => fake()->unique()->bothify('A-########'),
            'job_id' => Job::factory(),
            'candidate_id' => Candidate::factory()->afterCreating(function ($candidate) use (&$cvAttachmentId) {
                // Define the file path
                $directoryPath = 'fake_files';
                $fileName = 'cv_'.fake()->numberBetween(1000, 2000).'.pdf';
                $filePath = $directoryPath . '/' . $fileName;

                // Ensure the directory exists
                Storage::disk('local')->makeDirectory($directoryPath);

                // Create a dummy file with some content
                Storage::disk('local')->put($filePath, 'This is a dummy CV file.');

                // Assuming the Candidate model has a method to attach a CV
                $cvAttachment = Attachment::factory()->create([
                    'name' => 'CV',
                    'original_name' => $fileName,
                    'mime' => 'application/pdf',
                    'size' => Storage::disk('local')->size($filePath),
                    'path' => $filePath,
                    'extension' => 'pdf',
                    'user_id' => $candidate->user_id, // Assuming the candidate has a user_id
                ]);

                // Attach the fake CV to the candidate
                $candidate->attachment()->attach($cvAttachment->id, ['field_name' => 'cv']);

                // Capture the CV attachment ID to use for the 'cv' field
                $cvAttachmentId = $cvAttachment->id;
            }),
            'cv' => function () use (&$cvAttachmentId) {
                return $cvAttachmentId;
            },
            'cover_letter' => fake()->boolean(70) ? Attachment::factory() : null,
            'notes' => fake()->optional()->sentences(3, true),
        ];
    }
}
