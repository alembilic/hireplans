<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JobApplication;
use App\Models\Job;
use App\Models\Candidate;
// Assuming Attachment model exists for handling CVs and cover letters
use Orchid\Attachment\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_ref' => $this->faker->unique()->bothify('A-########'),
            'job_id' => Job::factory(),
            'candidate_id' => Candidate::factory(),
            'cv' => function (array $attributes) {
                $candidate = Candidate::find($attributes['candidate_id']);
                $directoryPath = 'fake_files';
                $fileName = 'cv_'.$this->faker->numberBetween(1000, 2000).'.pdf';
                $filePath = $directoryPath . '/' . $fileName;

                // Ensure the directory exists
                Storage::disk('local')->makeDirectory($directoryPath);

                // Create a dummy file with some content
                Storage::disk('local')->put($filePath, 'This is a dummy CV file.');

                // Create the attachment
                $cvAttachment = Attachment::factory()->create([
                    'name' => 'CV',
                    'original_name' => $fileName,
                    'mime' => 'application/pdf',
                    'size' => Storage::disk('local')->size($filePath),
                    'path' => $filePath,
                    'extension' => 'pdf',
                    'user_id' => $candidate->user_id,
                ]);

                // Attach the fake CV to the candidate
                $candidate->attachment()->attach($cvAttachment->id, ['field_name' => 'cv']);

                return $cvAttachment->id;
            },
            'cover_letter' => $this->faker->boolean(70) ? Attachment::factory() : null,
            'notes' => $this->faker->optional()->sentences(3, true),
        ];
    }
}
