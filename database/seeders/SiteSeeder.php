<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Organisation;
use App\Models\Site;
use App\Models\SiteType;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$organisations = Organisation::all();
		$countries = Country::all();

		foreach ($organisations as $organisation) {
			$siteTypes = SiteType::where('organisation_id', $organisation->id)->get();

			for ($i = 0; $i < 100; $i++) {
				Site::factory()->create([
					'organisation_id' => $organisation->id,
					'country_id' => $countries->random()->id,
					'site_type_id' => $siteTypes->random()->id
				]);
			}
		}
	}
}
