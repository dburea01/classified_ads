<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
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
        $roles = Role::all()->pluck('id');
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        return [
            'role_id' => $this->faker->randomElement($roles),
            'user_state_id' => $this->faker->boolean(90) ? 'VALIDATED' : 'CREATED',
            'first_name' => $firstName,
            'last_name' => $lastName,
            // 'email' => $this->faker->unique()->safeEmail,
            'email' => strtolower($firstName) . '.' . strtolower($lastName) . '@fakeAdressDomain.toto',
            'password' => Hash::make('azerty'),
            'email_verification_code' => $this->faker->word(),
            'email_verified_at' => $this->faker->dateTimeThisYear()
        ];
    }
}
