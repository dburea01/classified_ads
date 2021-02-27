<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Organization::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name' => $this->faker->company,
			'code' => $this->faker->word(),
			'comment' => $this->faker->sentence(random_int(6, 20)),
			'ads_max' => random_int(0, 100000),
			'status' => $this->faker->boolean(80) ? 'ACTIVE' : 'INACTIVE'
		];
	}
}
