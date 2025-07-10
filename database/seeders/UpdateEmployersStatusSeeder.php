<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employer;

class UpdateEmployersStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all employers that don't have a status value
        Employer::whereNull('status')->update(['status' => 6]); // UNCONTACTED

        $this->command->info('Updated ' . Employer::whereNotNull('status')->count() . ' employers with status value.');
    }
}
