<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Models\Site;
use App\Models\SiteType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    const CATEGORY_NAME = 'category name';

    public function testPostCategoryWithErrors(): void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/categories");

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'category_group_id',
                    // 'position',
                    'name',
                    'state_id'
                ]);
    }

    public function testPostCategoryErrors2(): void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $categoryToCreate = [
            'category_group_id' => 'fake',
            'name' => self::CATEGORY_NAME,
            'state_id' => 'FAKE'
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/categories", $categoryToCreate);

        $response->assertStatus(422);
    }

    public function testPostCategoryOk(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $categoryToCreate = [
            'category_group_id' => $categoryGroup->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'ACTIVE',
            'position' => 12
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/categories", $categoryToCreate);

        $response->assertStatus(201);
    }

    public function testPutCategoryWithErrors()
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $category = Category::factory()->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);

        $categoryToUpdate = [
            'category_group_id' => $categoryGroup->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'TOTO',
            'position' => '123'
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}", $categoryToUpdate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'state_id'
        ]);
    }

    public function testPutCategoryOk()
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $category = Category::factory()->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);

        $categoryToUpdate = [
            'category_group_id' => $categoryGroup->id,
            'name' => 'category name updated',
            'state_id' => 'ACTIVE',
            'position' => '123'
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}", $categoryToUpdate);
        $response->assertStatus(200);
    }

    public function testGetCategories() : void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        Category::factory()->count(10)->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id
        ]);
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/categories");

        $response->assertStatus(200);
    }

    public function testGetCategory() : void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $category = Category::factory()->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}");

        $response->assertStatus(200);
    }

    public function testGetCategoryFromAnotherOrganization() : void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $categoryGroupOther = CategoryGroup::factory()->create(['organization_id' => $organizationOther->id]);

        $categoryOther = Category::factory()->create([
            'organization_id' => $organizationOther->id,
            'category_group_id' => $categoryGroupOther->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/categories/{$categoryOther->id}");

        $response->assertStatus(404);
    }

    public function testDeleteCategory() :void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $category = Category::factory()->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id,
            'name' => self::CATEGORY_NAME,
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}");
        $response->assertStatus(404);
    }

    public function testSortCategories(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $categories = Category::factory()->count(3)->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id
        ]);

        $newOrder = [
            $categories[1]->id,
            $categories[2]->id,
            $categories[0]->id
        ];

        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->patch($this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}/sortCategories", $newOrder);

        $response->assertOk();

        $categoriesSorted = Category::where('organization_id', $organization->id)->where('category_group_id', $categoryGroup->id)->orderBy('position')->get();

        $this->assertEquals($categoriesSorted[0]->id, $newOrder[0]);
        $this->assertEquals($categoriesSorted[1]->id, $newOrder[1]);
        $this->assertEquals($categoriesSorted[2]->id, $newOrder[2]);
    }
}
