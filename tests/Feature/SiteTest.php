<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Organization;
use App\Models\Site;
use App\Models\SiteType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testPostSiteWithErrors(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'site_type_id',
            'country_id',
            'name',
            'zip_code',
            'city',
            'state_id'
        ]);
    }

    public function testPostSiteWithErrors2(): void
    {
        $organization = Organization::factory()->create();
        SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $siteToCreate = [
            'site_type_id' => 'fake',
            'country_id' => 'fake',
            'internal_id' => 'toto',
            'name' => 'site name',
            'state_id' => 'FAKE'
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites", $siteToCreate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'site_type_id',
            'country_id',
            'state_id'
        ]);
    }

    public function testPostSiteWithErrorInternalId(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);
        $site = Site::factory()->create([
            'organization_id' => $organization->id,
            'internal_id' => '123',
            'country_id' => 'FR'
        ]);

        $this->actingAsRole('ADMIN', $organization->id);

        $siteToCreate = [
            'site_type_id' => $siteType->id,
            'country_id' => 'FR',
            'internal_id' => $site->internal_id,
            'zip_code' => 59320,
            'city' => 'city',
            'name' => 'site name',
            'state_id' => 'ACTIVE',
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites", $siteToCreate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'internal_id'
        ]);
    }

    public function testPostSiteOk(): void
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $siteToCreate = [
            'site_type_id' => $siteType->id,
            'country_id' => 'FR',
            'internal_id' => '123',
            'zip_code' => 59320,
            'city' => 'city',
            'name' => 'site name',
            'state_id' => 'ACTIVE',
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/sites", $siteToCreate);

        $response->assertStatus(201);
    }

    public function testPutSiteWithErrors()
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);
        $site = Site::factory()->create([
            'organization_id' => $organization->id,
            'internal_id' => '123',
            'country_id' => 'FR'
        ]);

        $siteToUpdate = [
            'site_type_id' => $siteType->id,
            'country_id' => 'FR',
            'internal_id' => '123',
            'zip_code' => 59320,
            'city' => 'city',
            'name' => 'site name',
            'state_id' => 'FAKE',
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}", $siteToUpdate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'state_id'
        ]);
    }

    public function testPutSiteTypeOk()
    {
        $organization = Organization::factory()->create();
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);
        $site = Site::factory()->create([
            'organization_id' => $organization->id,
            'internal_id' => '123',
            'country_id' => 'FR'
        ]);

        $siteToUpdate = [
            'site_type_id' => $siteType->id,
            'country_id' => 'FR',
            'internal_id' => '123',
            'zip_code' => 59320,
            'city' => 'city',
            'name' => 'site name',
            'state_id' => 'INACTIVE',
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}", $siteToUpdate);

        $response->assertStatus(200);
    }

    public function testGetSites() : void
    {
        $organization = Organization::factory()->create();
        Site::factory()->count(10)->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites");

        $response->assertStatus(200);
    }

    public function testGetSite() : void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}");

        $response->assertStatus(200);
    }

    public function testDeleteSite() :void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/sites/{$site->id}");
        $response->assertStatus(404);
    }
}
