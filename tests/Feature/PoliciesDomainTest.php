<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PoliciesDomainTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testGetDomains(): void
    {
        $organization = Organization::factory()->create();
        Domain::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/domains")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/domains")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/domains")->assertForbidden();
    }

    public function testGetDomain(): void
    {
        $organization = Organization::factory()->create();
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertForbidden();
    }

    public function testPostDomain(): void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/domains")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/domains")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/domains")->assertForbidden();
    }

    public function testPutDomain(): void
    {
        $organization = Organization::factory()->create();
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertForbidden();
    }

    public function testDeleteDomain(): void
    {
        $organization = Organization::factory()->create();
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertForbidden();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}")->assertNoContent();
    }
}
