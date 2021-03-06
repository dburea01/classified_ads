<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
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
            Domain::factory()->count(10)->create([
                'organization_id' => $organization->id
            ]);
        }
    }
}
