<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PoliciesCategoryTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    private $organization;

    private $categoryGroup;

    private $categories;

    public function testGetCategoriesMyOrganization(): void
    {
        $this->createCategories();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/categories")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/categories")->assertOk();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/categories")->assertOk();
    }

    public function testGetCategoriesNotMyOrganisation(): void
    {
        $this->createCategories();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/categories")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/categories")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/categories")->assertForbidden();
    }

    public function testGetCategoryMyOrganization(): void
    {
        $this->createCategories();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertOk();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertOk();
    }

    public function testGetCategoryNotMyOrganization(): void
    {
        $this->createCategories();

        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[0]->id}")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[0]->id}")->assertNotFound();
    }

    public function testPostCategory(): void
    {
        $this->createCategories();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/categories")->assertStatus(422);

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/categories")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/categories")->assertForbidden();
    }

    public function testPostCategoryNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/categories")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/categories")->assertForbidden();
    }

    public function testPutCategory(): void
    {
        $this->createCategories();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertForbidden();
    }

    public function testPutCategoryNotMyOrganization(): void
    {
        $this->createCategories();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[0]->id}")->assertNotFound();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[0]->id}")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[0]->id}")->assertNotFound();
    }

    public function testDeleteCategoryMyOrganization(): void
    {
        $this->createCategories();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[0]->id}")->assertNoContent();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[1]->id}")->assertNoContent();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/categories/{$this->categories[2]->id}")->assertForbidden();
    }

    public function testDeleteSiteNotMyOrganization(): void
    {
        $this->createCategories();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[0]->id}")->assertNotFound();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[1]->id}")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/categories/{$this->categories[2]->id}")->assertNotFound();
    }

    public function testSortCategoriesMyOrganization(): void
    {
        $this->createCategories();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PATCH', $this->getUrl() . "/organizations/{$this->organization->id}/category-groups/{$this->categoryGroup->id}/sortCategories")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('PATCH', $this->getUrl() . "/organizations/{$this->organization->id}/category-groups/{$this->categoryGroup->id}/sortCategories")->assertOk();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('PATCH', $this->getUrl() . "/organizations/{$this->organization->id}/category-groups/{$this->categoryGroup->id}/sortCategories")->assertForbidden();
    }

    public function testSortCategoriesNotMyOrganization(): void
    {
        $this->createCategories();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PATCH', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$this->categoryGroup->id}/sortCategories")->assertNotFound();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('PATCH', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$this->categoryGroup->id}/sortCategories")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('PATCH', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$this->categoryGroup->id}/sortCategories")->assertNotFound();
    }

    public function createCategories()
    {
        $this->organization = Organization::factory()->create();
        $this->categoryGroup = CategoryGroup::factory()->create(['organization_id' => $this->organization->id]);

        $this->categories = Category::factory()->count(10)->create([
            'organization_id' => $this->organization->id,
            'category_group_id' => $this->categoryGroup->id
        ]);
    }
}
