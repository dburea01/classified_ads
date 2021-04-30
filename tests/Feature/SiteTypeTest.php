<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\SiteType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiteTypeTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testPostSiteTypeWithErrors(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/site-types");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'state_id'
        ]);
    }

    public function testPostSiteTypeOk(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);

        $siteTypeToCreate = [
            'name' => 'domain name',
            'state_id' => 'ACTIVE'
        ];

        $response = $this->post($this->getUrl() . "/organizations/{$organization->id}/site-types", $siteTypeToCreate);

        $response->assertStatus(201);
    }

    public function testPutSiteTypeWithErrors()
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'state_id'
        ]);
    }

    public function testPutSiteTypeOk()
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $siteTypeToModify = [
            'name' => 'domain name modif',
            'state_id' => 'INACTIVE'
        ];

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}", $siteTypeToModify);

        $response->assertStatus(200);
    }

    public function testGetSiteTypes() : void
    {
        $organization = Organization::factory()->create();
        SiteType::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types");

        $response->assertStatus(200);
    }

    public function testGetSiteType() : void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}");

        $response->assertStatus(200);
    }

    public function testDeleteSiteType() :void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}");
        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/site-types/{$siteType->id}");
        $response->assertStatus(404);
    }
}
