<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Organization;
use App\Models\SiteType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PoliciesSiteTypeTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testGetSiteTypesMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        SiteType::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertOk();
    }

    public function testGetSiteTypesNotMyOrganisation(): void
    {
        $organization = Organization::factory()->create();
        SiteType::factory()->count(10)->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        SiteType::factory()->count(10)->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types")->assertForbidden();
    }

    public function testGetSiteTypeMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertOk();
    }

    public function testGetSiteTypeNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $siteTypeOther = SiteType::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types/{$siteTypeOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types/{$siteTypeOther->id}")->assertForbidden();
    }

    public function testPostSiteType(): void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/site-types")->assertForbidden();
    }

    public function testPostSiteTypeNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types")->assertForbidden();
    }

    public function testPutSiteType(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertForbidden();
    }

    public function testPutSiteTypeNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        SiteType::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $siteTypeOther = SiteType::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types/{$siteTypeOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types/{$siteTypeOther->id}")->assertForbidden();
    }

    public function testDeleteSiteTypeMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertNoContent();

        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertNoContent();

        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);
        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}")->assertForbidden();
    }

    public function testDeleteSiteTypeNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        SiteType::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $siteTypeOther = SiteType::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types/{$siteTypeOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/site-types/{$siteTypeOther->id}")->assertForbidden();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteTypeOther->id}")->assertNotFound();
    }
}
