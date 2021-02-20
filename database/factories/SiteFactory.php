<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Site::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'name' => 'SITE ' . $this->faker->word(),
			'address1' => $this->faker->streetAddress(),
			'address2' => $this->faker->secondaryAddress(),
			'address3' => $this->faker->streetAddress(),
			'zip_code' => $this->faker->postcode,
			'city' => $this->faker->city,
			'status' => $this->faker->boolean(90) ? 'ACTIVE' : 'INACTIVE',
		];
	}
}
