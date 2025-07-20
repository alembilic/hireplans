<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employer;
use Faker\Factory as Faker;

class EmployerLogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        Employer::all()->each(function ($employer) use ($faker) {
            $employer->update([
                'logo' => $faker->imageUrl(400, 300, 'business', true, 'Faker'),
            ]);
        });
    }
}
