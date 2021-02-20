<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryGroup;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$categoryGroups = CategoryGroup::all();

		foreach ($categoryGroups as $categoryGroup) {
			Category::factory()->count(\random_int(5, 10))->create([
				'category_group_id' => $categoryGroup->id
			]);
		}
	}
}
