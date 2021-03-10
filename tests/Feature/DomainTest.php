<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DomainTest extends TestCase
{
    use DatabaseTransactions;
    // use WithoutMiddleware;
    use Request;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPostDomainWithoutBody(): void
    {
        $this->actingAsSuperAdmin(Organization::factory()->create());

        $organization = Organization::factory()->create();

        $response = $this->json('POST', $this->getUrl() . '/organizations/' . $organization->id . '/domains');

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
        ]);
    }

    public function testPostDomainOk(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsSuperAdmin($organization);

        $domainToCreate = [
            'name' => 'domain name'
        ];

        $response = $this->post($this->getUrl() . '/organizations/' . $organization->id . '/domains', $domainToCreate);
        $domainCreatedId = $response->decodeResponseJson()['data']['id'];
        $response->assertStatus(201);
    }

    public function testPutDomainWithErrors()
    {
        $organization = Organization::factory()->create();
        $this->actingAsSuperAdmin($organization);

        $response = $this->json('PUT', $this->getUrl() . '/organizations/' . $organization->id);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name'
        ]);
    }

    public function testPutDomainOk()
    {
        $organization = Organization::factory()->create();
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsSuperAdmin($organization);

        $domainToModify = [
            'name' => 'domain name modif'
        ];

        $response = $this->put($this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}", $domainToModify);

        $response->assertStatus(200);
    }

    public function testGetDomain() : void
    {
        $organization = Organization::factory()->create();
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsSuperAdmin($organization);

        $response = $this->get($this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}");

        $response->assertStatus(200);
    }

    public function testGetDomains() : void
    {
        $organization = Organization::factory()->create();
        Domain::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsSuperAdmin($organization);

        $response = $this->get($this->getUrl() . "/organizations/{$organization->id}/domains");

        $response->assertStatus(200);
    }

    public function testDeleteDomain() :void
    {
        $organization = Organization::factory()->create();
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsSuperAdmin($organization);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}");

        $response->assertStatus(204);
    }
}
