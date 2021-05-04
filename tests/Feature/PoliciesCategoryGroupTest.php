<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\CategoryGroup;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PoliciesCategoryGroupTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testGetCategoryGroupsMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->count(10)->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertOk();
    }

    public function testGetCategoryGroupsNotMyOrganisation(): void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->count(10)->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        CategoryGroup::factory()->count(10)->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups")->assertForbidden();
    }

    public function testGetCategoryGroupMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertOk();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertOk();
    }

    public function testGetCategoryGroupNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $categoryGroupOther = CategoryGroup::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertOk();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertForbidden();
    }

    public function testPostCategoryGroup(): void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/category-groups")->assertForbidden();
    }

    public function testPostCategoryGroupNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups")->assertForbidden();
    }

    public function testPutCategoryGroup(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertForbidden();
    }

    public function testPutCategoryGroupNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $categoryGroupOther = CategoryGroup::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertForbidden();
    }

    public function testDeleteCategoryGroupMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertNoContent();

        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertNoContent();

        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organization->id}/category-groups/{$categoryGroup->id}")->assertForbidden();
    }

    public function testDeleteCategoryGroupNotMyOrganization(): void
    {
        $organization = Organization::factory()->create();
        CategoryGroup::factory()->create(['organization_id' => $organization->id]);

        $organizationOther = Organization::factory()->create();
        $categoryGroupOther = CategoryGroup::factory()->create(['organization_id' => $organizationOther->id]);

        $this->actingAsRole('ADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertForbidden();

        $this->actingAsRole('SUPERADMIN', $organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/category-groups/{$categoryGroupOther->id}")->assertNoContent();
    }
}
