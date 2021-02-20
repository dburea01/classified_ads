<?php

namespace Database\Seeders;

use App\Models\Organisation;
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
		$organisations = Organisation::all();

		foreach ($organisations as $organisation) {
			User::factory()->count(1000)->create([
				'organisation_id' => $organisation->id
			]);

			User::factory()->create([
				'organisation_id' => $organisation->id,
				'is_admin' => true,
			]);
		}
	}
}
