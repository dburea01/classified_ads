<?php

namespace Database\Seeders;

use App\Models\ClassifiedAd;
use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassifiedAdSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$organizations = Organization::with('categories')->get();

		foreach ($organizations as $organization) {
			$sites = Site::where('organization_id', $organization->id)->get();
			$users = User::where('organization_id', $organization->id)->get();

			foreach ($users as $user) {
				for ($i = 0; $i < random_int(0, 5); $i++) {
					ClassifiedAd::factory()->create([
						'category_id' => $organization->categories->random()->id,
						'user_id' => $user->id,
						'site_id' => $sites->random()->id
					]);
				}
			}
		}
	}
}
