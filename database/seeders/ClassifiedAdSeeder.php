<?php

namespace Database\Seeders;

use App\Models\ClassifiedAd;
use App\Models\Organisation;
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
		$organisations = Organisation::with('categories')->get();

		foreach ($organisations as $organisation) {
			$sites = Site::where('organisation_id', $organisation->id)->get();
			$users = User::where('organisation_id', $organisation->id)->get();

			foreach ($users as $user) {
				for ($i = 0; $i < 4; $i++) {
					ClassifiedAd::factory()->create([
						'category_id' => $organisation->categories->random()->id,
						'user_id' => $user->id,
						'site_id' => $sites->random()->id
					]);
				}
			}
		}
	}
}
