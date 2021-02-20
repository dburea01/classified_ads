<?php

namespace Database\Seeders;

use App\Models\CategoryGroup;
use App\Models\Organisation;
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
		$organisations = Organisation::all();

		foreach ($organisations as $organisation) {
			CategoryGroup::factory()->count(\random_int(5, 10))->create([
				'organisation_id' => $organisation->id
			]);
		}
	}
}
