<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testGetUsers() : void
    {
        $organization = Organization::factory()->create();
        User::factory()->count(10)->create(['organization_id' => $organization->id, 'role_id' => 'EMPLOYEE']);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users");

        $response->assertStatus(200);
    }

    public function testGetUser() : void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id, 'role_id' => 'EMPLOYEE']);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}");

        $response->assertStatus(200);
    }

    public function testGetUserFromAnotherOrganizationShouldReturn404() : void
    {
        $organization = Organization::factory()->create();
        User::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $userOther = User::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/{$userOther->id}");

        $response->assertStatus(404);
    }

    public function testGetUserNotUuid() : void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/toto");

        $response->assertStatus(404);
    }

    public function testPutUserWithErrors()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id, 'role_id' => 'EMPLOYEE']);

        $userToUpdate = [
            'last_name' => 'last name updated',
            'first_name' => 'first name updated',
            'role_id' => 'SELLOR',
            'user_state_id' => 'TOTO'
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}", $userToUpdate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'role_id',
            'user_state_id'
        ]);
    }

    public function testPutUserOk()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id, 'role_id' => 'EMPLOYEE']);

        $userToUpdate = [
            'last_name' => 'last name updated',
            'first_name' => 'first name updated',
            'role_id' => 'EMPLOYEE',
            'user_state_id' => 'BLOCKED'
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}", $userToUpdate);

        $response->assertStatus(200);
    }

    public function testPutUserFromAnotherOrganizationShouldReturn404()
    {
        $organization = Organization::factory()->create();

        $organizationOther = Organization::factory()->create();
        $userOther = User::factory()->create(['organization_id' => $organizationOther->id]);

        $userToUpdate = [
            'last_name' => 'last name updated',
            'first_name' => 'first name updated',
            'role_id' => 'EMPLOYEE',
            'user_state_id' => 'BLOCKED'
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$userOther->id}", $userToUpdate);

        $response->assertStatus(404);
    }

    public function testDeleteUser() :void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id, 'role_id' => 'EMPLOYEE']);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}");
        $response->assertStatus(404);
    }

    public function testDeleteUserFromAnotherOrganizationShouldReturn404() :void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $organization->id);
        $organizationOther = Organization::factory()->create();
        $userOther = User::factory()->create(['organization_id' => $organizationOther->id]);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/users/{$userOther->id}");

        $response->assertStatus(404);
    }
}
