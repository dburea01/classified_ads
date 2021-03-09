<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;
    use Request;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPostOrganizationWithoutBody(): void
    {
        // $response = $this->post($this->getUrl() . '/organizations');
        $response = $this->json('POST', $this->getUrl() . '/organizations');

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'contact',
            'state_id'
        ]);
    }

    public function testPostOrganizationWithErrors(): void
    {
        $response = $this->json('POST', $this->getUrl() . '/organizations', [
            'name' => 'a',
            'contact' => '',
            'ads_max' => -1,
            'stated_id' => 'UNKNOWN'
        ]);
        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'contact',
            'state_id'
        ]);
    }

    public function testPostOrganizationOk(): void
    {
        $organization = Organization::factory()->create();
        $superAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role_id' => 'SUPERADMIN'
        ]);
        Sanctum::actingAs($superAdmin);
        $organizationToCreate = [
            'name' => 'organization name',
            'contact' => 'organization contact',
            'comment' => 'organization comment',
            'ads_max' => 123,
            'state_id' => 'VALIDATED'
        ];

        $response = $this->json('POST', $this->getUrl() . '/organizations', $organizationToCreate);
        $organizationCreatedId = $response->decodeResponseJson()['data']['id'];
        $response->assertStatus(201);

        $organizationCreated = Organization::find($organizationCreatedId);
        $this->assertEquals($organizationCreated->name, $organizationToCreate['name']);
        $this->assertEquals($organizationCreated->comment, $organizationToCreate['comment']);
        $this->assertEquals($organizationCreated->contact, $organizationToCreate['contact']);
        $this->assertEquals($organizationCreated->ads_max, $organizationToCreate['ads_max']);
        $this->assertEquals($organizationCreated->state_id, $organizationToCreate['state_id']);
    }

    public function testPutOrganizationWithErrors()
    {
        $organization = Organization::factory()->create();

        $response = $this->json('PUT', $this->getUrl() . '/organizations/' . $organization->id);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'contact',
            'state_id'
        ]);
    }

    public function testPutOrganizationOk()
    {
        $organization = Organization::factory()->create();

        $superAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role_id' => 'SUPERADMIN'
        ]);

        Sanctum::actingAs($superAdmin);

        $organizationToModify = [
            'name' => 'mon organization moif',
            'contact' => 'contact name modif',
            'comment' => 'comment comment comment modif',
            'ads_max' => 12345,
            'state_id' => 'VALIDATED'
        ];

        $response = $this->json('PUT', $this->getUrl() . '/organizations/' . $organization->id, $organizationToModify);
        // $response->dump();
        $response->assertStatus(200);

        /*
        ->assertJson([
            'data' => [
                'id' => $organization->id,
                'name' => $organizationToModify['name'],
                'contact' => $organizationToModify['contact'],
                'comment' => $organizationToModify['comment'],
                'ads_max' => $organizationToModify['ads_max'],
                'state_id' => $organizationToModify['state_id'],
            ]
        ]);
        */
    }

    public function testGetOrganization() : void
    {
        $organization = Organization::factory()->create();

        $response = $this->get($this->getUrl() . '/organizations/' . $organization->id);
        // $response->dump();
        $response->assertStatus(200);

        /*
        $response->assertJson([
            'data' => [
                'id' => $organization->id,
                'name' => $organization->name
            ]
        ]);
        */
    }

    public function testGetOrganizations() : void
    {
        $organization = Organization::factory()->create();

        $response = $this->get($this->getUrl() . '/organizations/');

        $response->assertStatus(200);
    }

    public function testDeleteOrganization() :void
    {
        $organization = Organization::factory()->create();

        $superAdmin = User::factory()->create([
            'organization_id' => $organization->id,
            'role_id' => 'SUPERADMIN'
        ]);

        Sanctum::actingAs($superAdmin);
        $response = $this->json('DELETE', $this->getUrl() . '/organizations/' . $organization->id);

        $response->assertStatus(204);
    }
}
