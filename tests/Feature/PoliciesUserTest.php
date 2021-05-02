<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PoliciesUserTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testGetUsersMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        User::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users")->assertForbidden();
    }

    public function testGetUsersNotMyOrganisation(): void
    {
        $organization = Organization::factory()->create();
        User::factory()->count(10)->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        User::factory()->count(10)->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/users")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/users")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/users")->assertForbidden();
    }

    public function testGetUserMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertForbidden();
    }

    public function testGetUserNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        User::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $userOther = User::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertForbidden();
    }

    public function testGetUserMe(): void
    {
        $organization = Organization::factory()->create();

        $userMe = $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/users/{$userMe->id}")->assertOk();
    }

    public function testPostUserMethodNotAllowed(): void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/users")->assertStatus(405);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/users")->assertStatus(405);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/users")->assertStatus(405);
    }

    public function testPutUser(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertForbidden();
    }

    public function testPutUserNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        User::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $userOther = User::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertForbidden();
    }

    public function testPutUserMe(): void
    {
        $organization = Organization::factory()->create();

        $userMe = $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/users/{$userMe->id}")->assertStatus(422);
    }

    public function testDeleteUserMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertNoContent();

        $user = User::factory()->create(['organization_id' => $organization->id]);
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertNoContent();

        $user = User::factory()->create(['organization_id' => $organization->id]);
        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/users/{$user->id}")->assertForbidden();
    }

    public function testDeleteUserNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        User::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $userOther = User::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/users/{$userOther->id}")->assertForbidden();
    }

    public function testDeleteUserMe(): void
    {
        $organization = Organization::factory()->create();

        $userMe = $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/users/{$userMe->id}")->assertForbidden();
    }
}
