<?php

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganisationFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Organisation::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name' => $this->faker->company,
			'comment' => $this->faker->sentence(random_int(6, 20)),
			'status' => $this->faker->boolean(80) ? 'ACTIVE' : 'INACTIVE'
		];
	}
}
