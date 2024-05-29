<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Role::class, 5)->create();
        Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'platform.index'              => 1,
                'platform.systems'            => 1,
                'platform.systems.roles'      => 1,
                'platform.systems.settings'   => 1,
                'platform.systems.users'      => 1,
                'platform.systems.attachment' => 1,
                'platform.systems.media'      => 1,
            ],
        ]);

        Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'permissions' => [
                'platform.index'              => 1,
                'platform.systems'            => 1,
                'platform.systems.roles'      => 1,
                'platform.systems.settings'   => 1,
                'platform.systems.users'      => 1,
                'platform.systems.attachment' => 1,
                'platform.systems.media'      => 1,
            ],
        ]);

        Role::create([
            'name' => 'Authenticated User',
            'slug' => 'authenticated_user',
            'permissions' => [
                // 'platform.index'              => 1,
            ],
        ]);

        Role::create([
            'name' => 'Candidate',
            'slug' => 'candidate',
            'permissions' => [
                'platform.index'              => 1,
                'platform.systems.attachment' => 1,
            ],
        ]);

        Role::create([
            'name' => 'Employer',
            'slug' => 'employer',
            'permissions' => [
                'platform.index'              => 1,
                'platform.systems.attachment' => 1,
            ],
        ]);

    }
}
