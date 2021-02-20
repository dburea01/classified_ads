<?php

namespace Database\Seeders;

use App\Models\Organisation;
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
		$organisations = Organisation::all();

		foreach ($organisations as $organisation) {
			SiteType::factory()->count(4)->create([
				'organisation_id' => $organisation->id,
			]);
		}
	}
}
