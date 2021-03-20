<?php

namespace Database\Factories;

use App\Models\ClassifiedAd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassifiedAdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassifiedAd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(\random_int(3, 10)),
            'price' => random_int(1, 100),
            'currency_id' => $this->faker->randomElement(['EUR', 'GBP', 'USD']),
            'description' => $this->faker->text(100),
            'ads_status_id' => $this->faker->randomElement(['CREATED', 'VALIDATED', 'ARCHIVED']),
        ];
    }
}
