<?php

namespace Database\Factories;

use App\Models\SiteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteTypeFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = SiteType::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name' => 'SITE TYPE ' . $this->faker->word(),
			'status' => 'ACTIVE'
		];
	}
}
