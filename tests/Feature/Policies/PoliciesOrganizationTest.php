<?php

namespace Tests\Feature\Policies;

use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use tests\Feature\Request;

class PoliciesOrganizationTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    const URL = '/organizations';

    // superadmin role
    public function testGetOrganizations(): void
    {
        $organizations = Organization::factory()->count(10)->create();
        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . self::URL)->assertOk();

        $this->actingAsRole('ADMIN', $organizations[0]->id);
        $this->json('GET', $this->getUrl() . self::URL)->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organizations[0]->id);
        $this->json('GET', $this->getUrl() . self::URL)->assertForbidden();
    }

    public function testGetOrganization(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(200);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(403);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(403);
    }

    public function testPostOrganization(): void
    {
        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . self::URL)->assertStatus(422);

        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . self::URL)->assertStatus(403);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . self::URL)->assertStatus(403);
    }

    public function testPutOrganization(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(422);

        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(403);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(403);
    }

    public function testDeleteOrganization(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(204);

        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(403);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}")->assertStatus(403);
    }
}
