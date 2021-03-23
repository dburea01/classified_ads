<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::factory()->count(5)->create();
        Organization::factory()->create(['name' => 'decathlon', 'container_folder' => 'decathlon']);
        Organization::factory()->create(['name' => 'boulanger', 'container_folder' => 'boulanger']);
    }
}
