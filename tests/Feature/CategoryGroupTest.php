<?php

namespace Tests\Feature;

use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Models\SiteType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CategoryGroupTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testPostCategoryGroupWithErrors(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/category-groups");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'state_id',
        ]);
    }

    public function testPostCategoryGroupOk(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('ADMIN', $organization->id);

        $categoryGroupToCreate = [
            'name' => 'domain name',
            'state_id' => 'ACTIVE',
            'position' => '10'
        ];

        $response = $this->post($this->getUrl() . "/organizations/{$organization->id}/category-groups", $categoryGroupToCreate);

        $response->assertStatus(201);
    }

    public function testPutCategoryGroupWithErrors()
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'state_id'
        ]);
    }

    public function testPutCategoryGroupOk()
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $categoryGroupToModify = [
            'name' => 'domain name modif',
            'state_id' => 'INACTIVE'
        ];

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}", $categoryGroupToModify);

        $response->assertStatus(200);
    }

    public function testGetCategoryGroups() : void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups");

        $response->assertStatus(200);
    }

    public function testGetCategoryGroup() : void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}");

        $response->assertStatus(200);
    }

    public function testGetCategoryGroupFromAnotherOrganization() : void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $categoryGroupOther = CategoryGroup::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroupOther->id}");

        $response->assertStatus(404);
    }

    public function testDeleteCategoryGroup() :void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}");
        $response->assertStatus(404);
    }

    public function testSortCategoryGroup(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroups = CategoryGroup::factory()->count(3)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $newOrder = [
            $categoryGroups[0]->id,
            $categoryGroups[1]->id,
            $categoryGroups[2]->id
        ];

        $response = $this->patch($this->getUrl() . "/organizations/{$organization->id}/category-groups/sort", $newOrder);
        $response->assertOk();

        $categoryGroupsSorted = CategoryGroup::where('organization_id', $organization->id)->orderBy('position')->get();

        $this->assertEquals($newOrder[0], $categoryGroupsSorted[0]->id);
        $this->assertEquals($newOrder[1], $categoryGroupsSorted[1]->id);
        $this->assertEquals($newOrder[2], $categoryGroupsSorted[2]->id);

        $this->assertEquals(0, $categoryGroupsSorted[0]->position);
        $this->assertEquals(1, $categoryGroupsSorted[1]->position);
        $this->assertEquals(2, $categoryGroupsSorted[2]->position);
    }
}
