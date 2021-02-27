<?php

namespace Database\Seeders;

use App\Models\Organization;
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
			User::factory()->count(1000)->create([
				'organization_id' => $organization->id
			]);

			User::factory()->create([
				'organization_id' => $organization->id,
				'is_admin' => true,
			]);
		}
	}
}
