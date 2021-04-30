<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    const URL = '/organizations';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPostOrganizationWithoutBody(): void
    {
        $this->actingAsRole('SUPERADMIN', null);

        $response = $this->json('POST', $this->getUrl() . self::URL);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'contact',
            'state_id'
        ]);
    }

    public function testPostOrganizationWithErrors(): void
    {
        $this->actingAsRole('SUPERADMIN', null);

        $response = $this->json('POST', $this->getUrl() . self::URL, [
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
        $this->actingAsRole('SUPERADMIN', null);

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
            'state_id' => 'VALIDATED',
            'container_folder' => 'container name folder'
        ];

        $response = $this->json('POST', $this->getUrl() . self::URL, $organizationToCreate);
        $response->assertStatus(201);
        $organizationCreatedId = $response->decodeResponseJson()['data']['id'];

        $organizationCreated = Organization::find($organizationCreatedId);
        $this->assertEquals($organizationCreated->name, $organizationToCreate['name']);
        $this->assertEquals($organizationCreated->comment, $organizationToCreate['comment']);
        $this->assertEquals($organizationCreated->contact, $organizationToCreate['contact']);
        $this->assertEquals($organizationCreated->ads_max, $organizationToCreate['ads_max']);
        $this->assertEquals($organizationCreated->state_id, $organizationToCreate['state_id']);
    }

    public function testPutOrganizationWithErrors()
    {
        $this->actingAsRole('SUPERADMIN', null);

        $organization = Organization::factory()->create();

        $response = $this->json('PUT', $this->getUrl() . self::URL . '/' . $organization->id);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'contact',
            'state_id'
        ]);
    }

    public function testPutOrganizationOk()
    {
        $this->actingAsRole('SUPERADMIN', null);

        $organization = Organization::factory()->create();

        $organizationToModify = [
            'name' => 'mon organization moif',
            'contact' => 'contact name modif',
            'comment' => 'comment comment comment modif',
            'ads_max' => 12345,
            'state_id' => 'VALIDATED',
            'container_folder' => 'container name folder'
        ];

        $response = $this->put($this->getUrl() . self::URL . '/' . $organization->id, $organizationToModify);

        $response->assertStatus(200);
    }

    public function testGetOrganization() : void
    {
        $this->actingAsRole('SUPERADMIN', null);
        $organization = Organization::factory()->create();

        $response = $this->json('GET', $this->getUrl() . self::URL . '/' . $organization->id);

        $response->assertStatus(200);
    }

    public function testGetOrganizations() : void
    {
        $this->actingAsRole('SUPERADMIN', null);
        Organization::factory()->count(10)->create();

        $response = $this->get($this->getUrl() . self::URL);
        $response->assertStatus(200);
    }

    public function testDeleteOrganization() :void
    {
        $this->actingAsRole('SUPERADMIN', null);

        $organization = Organization::factory()->create();
        $response = $this->delete($this->getUrl() . self::URL . '/' . $organization->id);

        $response->assertStatus(204);
    }
}
