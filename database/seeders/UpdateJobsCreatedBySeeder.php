<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class UpdateJobsCreatedBySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one if none exists
        $adminUser = User::whereHas('roles', function ($query) {
            $query->where('slug', 'admin');
        })->first();

        if (!$adminUser) {
            // If no admin user exists, use the first user or create one
            $adminUser = User::first();
            
            if (!$adminUser) {
                // Create a default user if none exists
                $adminUser = User::factory()->create([
                    'name' => 'System Admin',
                    'email' => 'admin@hireplans.com',
                ]);
            }
        }

        // Update all jobs that don't have a created_by value
        Job::whereNull('created_by')->update(['created_by' => $adminUser->id]);

        $this->command->info('Updated ' . Job::whereNotNull('created_by')->count() . ' jobs with created_by value.');
    }
}
