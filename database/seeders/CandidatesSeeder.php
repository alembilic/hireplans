<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;
use App\Models\Candidate;
use Orchid\Attachment\Models\Attachment;

class CandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Candidate::factory()->count(20)->create();

        $candidateRole = Role::where('slug', 'candidate')->first();
        $authUserRole = Role::where('slug', 'authenticated_user')->first();

        Candidate::factory()->count(20)->create()->each(
            function ($candidate) use ($candidateRole, $authUserRole) {
                $user = $candidate->user;
                $user->roles()->attach([$candidateRole->id, $authUserRole->id]);

                // Simulate creating a fake CV attachment
                $attachment = Attachment::create([
                    'name' => 'Fake CV',
                    'original_name' => 'fake_cv_'.fake()->numberBetween(1000, 2000).'.pdf',
                    'mime' => 'application/pdf',
                    'size' => fake()->numberBetween(10000, 50000), // Simulate file size in bytes
                    'path' => 'path/to/fake_cv.pdf',
                    'extension' => 'pdf',
                    'user_id' => $candidate->user_id, // Assuming the candidate has a user_id
                ]);

                // Attach the fake CV to the candidate
                $candidate->attachment()->attach($attachment->id, ['field_name' => 'cv']);
            });
    }
}
