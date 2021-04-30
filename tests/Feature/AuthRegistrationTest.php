<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\SendEmailValidateUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    const URL = '/register';

    const FIRST_NAME = 'First name';

    const LAST_NAME = 'Last name';

    public function testRegisterWithoutCredentials(): void
    {
        $response = $this->postJson($this->getUrl() . self::URL);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'organization_id',
            'email',
            'password'
        ]);
    }

    public function testRegisterWithErrors(): void
    {
        $response = $this->postJson($this->getUrl() . self::URL, [
            'email' => 'wrongemail',
            'first_name' => self::FIRST_NAME,
            'last_name' => self::LAST_NAME,
            'password' => 'short',
            'password_confirmation' => 'password',
            'organization_id' => 'wrongorganization'
        ]);
        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
            'password',
            'organization_id'
        ]);
    }

    public function testRegisterWithAWrongEmailDomain(): void
    {
        $organization = Organization::factory()->create();

        Domain::factory()->count(2)->create([
            'organization_id' => $organization->id
        ]);

        $response = $this->postJson($this->getUrl() . self::URL, [
            'email' => 'email.email@email.fr',
            'first_name' => self::FIRST_NAME,
            'last_name' => self::LAST_NAME,
            'password' => 'azerty',
            'password_confirmation' => 'azerty',
            'organization_id' => $organization->id
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function testRegisterWithAnExistingCredentials(): void
    {
        $organization = Organization::factory()->create();

        $user = User::factory()->create([
            'organization_id' => $organization->id,
            'role_id' => 'EMPLOYEE',
            'user_state_id' => 'VALIDATED'
        ]);

        $response = $this->postJson($this->getUrl() . self::URL, [
            'email' => $user->email,
            'first_name' => self::FIRST_NAME,
            'last_name' => self::LAST_NAME,
            'password' => 'azerty',
            'password_confirmation' => 'azerty',
            'organization_id' => $organization->id
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
        ]);
    }

    public function testRegisterOk(): void
    {
        Notification::fake();

        $organization = Organization::factory()->create();

        $domain = Domain::factory()->create([
            'organization_id' => $organization->id
        ]);

        $userToRegister = [
            'first_name' => self::FIRST_NAME,
            'last_name' => self::LAST_NAME,
            'email' => 'firstname.lastname@' . $domain->name,
            'password' => 'azerty'
        ];

        $response = $this->postJson($this->getUrl() . self::URL, [
            'email' => $userToRegister['email'],
            'first_name' => $userToRegister['first_name'],
            'last_name' => $userToRegister['last_name'],
            'password' => $userToRegister['password'],
            'password_confirmation' => $userToRegister['password'],
            'organization_id' => $organization->id
        ]);

        $response->assertStatus(201);

        $userRegistred = User::where('organization_id', $organization->id)->where('email', $userToRegister['email'])->first();
        $this->assertEquals($userToRegister['first_name'], $userRegistred->first_name);
        $this->assertEquals($userToRegister['last_name'], $userRegistred->last_name);
        $this->assertEquals($userToRegister['email'], $userRegistred->email);
        $this->assertEquals($organization->id, $userRegistred->organization_id);
        $this->assertEquals($userRegistred->user_state_id, 'CREATED');
        $this->assertEquals($userRegistred->role_id, 'EMPLOYEE');

        Notification::assertSentTo($userRegistred, SendEmailValidateUser::class);
    }
}
