<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use App\Models\Job;
use App\Models\Candidate;

class JobApplicationSeeder extends Seeder
{
    public function run()
    {
        // Create some jobs and candidates
        $jobs = Job::factory()->count(10)->create();
        $candidates = Candidate::factory()->count(10)->create();

        // Track job-candidate pairs to avoid duplicates
        $createdApplications = [];

        // Assign job applications randomly for each job
        foreach ($jobs as $job) {
            $applicationsCount = rand(0, 5); // Random number of applications per job
            for ($i = 0; $i < $applicationsCount; $i++) {
                $candidate = $candidates->random();
                $key = $job->id . '-' . $candidate->id;

                // Skip if the job-candidate pair already exists
                if (isset($createdApplications[$key])) {
                    continue;
                }

                JobApplication::factory()->create([
                    'job_id' => $job->id,
                    'candidate_id' => $candidate->id,
                ]);

                // Mark this job-candidate pair as created
                $createdApplications[$key] = true;
            }
        }

        // Assign job applications randomly for each candidate
        foreach ($candidates as $candidate) {
            $applicationsCount = rand(0, 5); // Random number of applications per candidate
            for ($i = 0; $i < $applicationsCount; $i++) {
                $job = $jobs->random();
                $key = $job->id . '-' . $candidate->id;

                // Skip if the job-candidate pair already exists
                if (isset($createdApplications[$key])) {
                    continue;
                }

                JobApplication::factory()->create([
                    'candidate_id' => $candidate->id,
                    'job_id' => $job->id,
                ]);

                // Mark this job-candidate pair as created
                $createdApplications[$key] = true;
            }
        }
    }
}

