<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'CATEGORY ' . $this->faker->word(),
            'position' => \random_int(1, 10),
            'state_id' => $this->faker->boolean(80) ? 'ACTIVE' : 'INACTIVE'
        ];
    }
}
