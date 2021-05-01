<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PoliciesSiteTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testGetSitesMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        Site::factory()->count(10)->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertOk();
    }

    public function testGetSitesNotMyOrganisation(): void
    {
        $organization = Organization::factory()->create();
        Site::factory()->count(10)->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $organizationOther = Organization::factory()->create();
        Site::factory()->count(10)->create(['organization_id' => $organizationOther->id, 'country_id' => 'FR']);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/sites")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/sites")->assertForbidden();
    }

    public function testGetSiteMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertOk();
    }

    public function testGetSiteNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $organizationOther = Organization::factory()->create();
        $siteOther = Site::factory()->create(['organization_id' => $organizationOther->id, 'country_id' => 'FR']);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/sites/{$siteOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/sites/{$siteOther->id}")->assertForbidden();
    }

    public function testPostSite(): void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites")->assertForbidden();
    }

    public function testPostSiteNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/sites")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/sites")->assertForbidden();
    }

    public function testPutSite(): void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertForbidden();
    }

    public function testPutSiteNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $organizationOther = Organization::factory()->create();
        $siteOther = Site::factory()->create(['organization_id' => $organizationOther->id, 'country_id' => 'FR']);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/sites/{$siteOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/sites/{$siteOther->id}")->assertForbidden();
    }

    public function testDeleteSiteMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertNoContent();

        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertNoContent();

        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}")->assertForbidden();
    }

    public function testDeleteSiteNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $organizationOther = Organization::factory()->create();
        $siteOther = Site::factory()->create(['organization_id' => $organizationOther->id, 'country_id' => 'FR']);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/sites/{$siteOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/sites/{$siteOther->id}")->assertForbidden();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteOther->id}")->assertNotFound();
    }
}
