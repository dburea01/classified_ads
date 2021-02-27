<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\SiteType;
use Illuminate\Database\Seeder;

class SiteTypeSeeder extends Seeder
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
			SiteType::factory()->count(4)->create([
				'organization_id' => $organization->id,
			]);
		}
	}
}
