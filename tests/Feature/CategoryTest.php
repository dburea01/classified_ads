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

    public function testPostCategoryWithErrors(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

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
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('ADMIN', $organization->id);

        $categoryToCreate = [
            'category_group_id' => 'fake',
            'name' => 'category name',
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
            'name' => 'category name',
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
            'name' => 'category name',
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);

        $categoryToUpdate = [
            'category_group_id' => $categoryGroup->id,
            'name' => 'category name',
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
            'name' => 'category name',
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

        $categories = Category::factory()->count(10)->create([
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
            'name' => 'category name',
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}");

        $response->assertStatus(200);
    }

    public function testDeleteCategory() :void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $category = Category::factory()->create([
            'organization_id' => $organization->id,
            'category_group_id' => $categoryGroup->id,
            'name' => 'category name',
            'state_id' => 'ACTIVE',
            'position' => '123'
        ]);
        $this->actingAsRole('ADMIN', $organization->id);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/categories/{$category->id}");
        $response->assertStatus(404);
    }
}
