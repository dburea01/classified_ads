<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DomainTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    const URL = '/organizations/';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPostDomainWithoutBody(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);

        $response = $this->json('POST', $this->getUrl() . self::URL . $organization->id . '/domains');

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
        ]);
    }

    public function testPostDomainOk(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);

        $domainToCreate = [
            'name' => 'domain name'
        ];

        $response = $this->post($this->getUrl() . self::URL . $organization->id . '/domains', $domainToCreate);
        $response->decodeResponseJson()['data']['id'];
        $response->assertStatus(201);
    }

    public function testPutDomainWithErrors()
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . self::URL . $organization->id);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name'
        ]);
    }

    public function testPutDomainOk()
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $domainToModify = [
            'name' => 'domain name modif'
        ];

        $response = $this->put($this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}", $domainToModify);

        $response->assertStatus(200);
    }

    public function testGetDomain() : void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $response = $this->get($this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}");

        $response->assertStatus(200);
    }

    public function testGetDomains() : void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);
        Domain::factory()->count(10)->create(['organization_id' => $organization->id]);

        $response = $this->get($this->getUrl() . "/organizations/{$organization->id}/domains");

        $response->assertStatus(200);
    }

    public function testDeleteDomain() :void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('SUPERADMIN', $organization->id);
        $domain = Domain::factory()->create(['organization_id' => $organization->id]);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/domains/{$domain->id}");

        $response->assertStatus(204);
    }
}
