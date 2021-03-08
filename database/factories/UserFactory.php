<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'state_id' => $this->faker->boolean(90) ? 'VALIDATED' : 'CREATED',
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('azerty'),
            'email_verification_code' => $this->faker->word(),
            'email_verified_at' => $this->faker->dateTimeThisYear()
        ];
    }
}
