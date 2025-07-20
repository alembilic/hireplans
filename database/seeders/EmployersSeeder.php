<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;
use App\Models\Employer;

class EmployersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Employer::factory()->count(20)->create();

        $employerRole = Role::where('slug', 'employer')->first();
        $authUserRole = Role::where('slug', 'authenticated_user')->first();

        Employer::factory()->count(20)->create()->each(
            function ($employer) use ($employerRole, $authUserRole) {
                $user = $employer->user;
                $user->roles()->attach([$employerRole->id, $authUserRole->id]);
            });
    }
}
