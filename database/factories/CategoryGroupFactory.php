<?php

namespace Database\Factories;

use App\Models\CategoryGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryGroupFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = CategoryGroup::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'position' => random_int(1, 10),
			'name' => 'GATEGORY GROUP ' . $this->faker->word(),
			'status' => $this->faker->boolean(80) ? 'ACTIVE' : 'INACTIVE'
		];
	}
}
