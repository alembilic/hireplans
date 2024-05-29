<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;
use App\Models\Candidate;

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
            });
    }
}
