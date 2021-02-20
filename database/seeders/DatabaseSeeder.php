<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		// \App\Models\User::factory(10)->create();

		$this->call([
			OrganisationSeeder::class,
			CategoryGroupSeeder::class,
			CategorySeeder::class,
			UserSeeder::class,
			SiteTypeSeeder::class,
			SiteSeeder::class,
			ClassifiedAdSeeder::class
		]);
	}
}
