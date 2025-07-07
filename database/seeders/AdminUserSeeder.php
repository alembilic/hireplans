<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@barasoft.co.uk',
            'email_verified_at' => now(),
            'password' => '$2y$12$iK7HRaI/oaQjwVOXmwKRFONzBFK4aIC.LFrVDJIfe2Ozq6Zbnma1G', // This is already hashed
            'remember_token' => 'LMPlEPrQGiw9BuW6Ax29DRgMMkFeez68iXZPItqLR8eh1UYUgzif26BgPYeC',
            'phone' => null,
            'address_line_1' => null,
            'city' => null,
            'postcode' => null,
            'country' => null,
            'nationality' => null,
            'dob' => null,
            'permissions' => [
                'platform.index' => true,
                'platform.systems.roles' => true,
                'platform.systems.users' => true,
                'platform.systems.attachment' => true,
            ],
        ]);
    }
}
