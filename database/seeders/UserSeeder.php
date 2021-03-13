<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            User::factory()->count(random_int(1, 10))->create([
                'organization_id' => $organization->id,
                'role_id' => 'EMPLOYEE'
            ]);

            User::factory()->create([
                'organization_id' => $organization->id,
                'role_id' => 'ADMIN',
            ]);
        }

        User::factory()->create([
            'role_id' => 'SUPERADMIN',
        ]);
    }
}
