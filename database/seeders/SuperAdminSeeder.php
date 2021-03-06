<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmins = User::take(10)->get();

        foreach ($superAdmins as $superAdmin) {
            SuperAdmin::factory()->create([
                'id' => $superAdmin->id
            ]);
        }
    }
}
