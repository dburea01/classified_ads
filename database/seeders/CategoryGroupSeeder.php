<?php

namespace Database\Seeders;

use App\Models\CategoryGroup;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class CategoryGroupSeeder extends Seeder
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
			CategoryGroup::factory()->count(\random_int(5, 10))->create([
				'organization_id' => $organization->id
			]);
		}
	}
}
