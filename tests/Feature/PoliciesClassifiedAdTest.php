<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\ClassifiedAd;
use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PoliciesClassifiedAdTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    private $organization;

    private $categoryGroup;

    private $category;

    private $site;

    private $classifiedAds;

    public function testGetClassifiedAdsMyOrganization(): void
    {
        $this->createClassifiedAds();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads")->assertOk();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads")->assertOk();
    }

    public function testGetClassifiedAdsNotMyOrganization(): void
    {
        $this->createClassifiedAds();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads")->assertForbidden();
    }

    public function testGetClassifiedAdMyOrganization(): void
    {
        $this->createClassifiedAds();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertOk();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertOk();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertOk();
    }

    public function testGetClassifiedAdNotMyOrganization(): void
    {
        $this->createClassifiedAds();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNotFound();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('GET', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNotFound();
    }

    public function testPostClassifiedAd(): void
    {
        $this->createClassifiedAds();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads")->assertStatus(422);

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads")->assertStatus(422);
    }

    public function testPostClassifiedAdNotMyOrganization(): void
    {
        $this->createClassifiedAds();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads")->assertStatus(422);

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads")->assertForbidden();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('POST', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads")->assertForbidden();
    }

    public function testPutClassifiedAd(): void
    {
        $this->createClassifiedAds();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertStatus(422);

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertStatus(422);

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertForbidden();
    }

    public function testPutMyClassifiedAd(): void
    {
        $this->createClassifiedAds();

        $userMe = $this->actingAsRole('EMPLOYEE', $this->organization->id);

        $myClassifiedAd = ClassifiedAd::find($this->classifiedAds[0]->id);

        $myClassifiedAd->user_id = $userMe->id;
        $myClassifiedAd->save();

        $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$myClassifiedAd->id}")->assertStatus(422);
    }

    public function testPutClassifiedAdNotMyOrganization(): void
    {
        $this->createClassifiedAds();

        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('PUT', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNotFound();
    }

    public function testDeleteClassifiedAdMyOrganization(): void
    {
        $this->createClassifiedAds();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNoContent();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[1]->id}")->assertNoContent();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$this->classifiedAds[2]->id}")->assertForbidden();
    }

    public function testDeleteSiteNotMyOrganization(): void
    {
        $this->createClassifiedAds();
        $organizationOther = Organization::factory()->create();

        $this->actingAsRole('SUPERADMIN', null);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[0]->id}")->assertNotFound();

        $this->actingAsRole('ADMIN', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[1]->id}")->assertNotFound();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $this->json('DELETE', $this->getUrl() . "/organizations/{$organizationOther->id}/classified-ads/{$this->classifiedAds[2]->id}")->assertNotFound();
    }

    public function testDeleteMyClassifiedAd(): void
    {
        $this->createClassifiedAds();

        $userMe = $this->actingAsRole('EMPLOYEE', $this->organization->id);

        $myClassifiedAd = ClassifiedAd::find($this->classifiedAds[0]->id);

        $myClassifiedAd->user_id = $userMe->id;
        $myClassifiedAd->save();

        $this->json('DELETE', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$myClassifiedAd->id}")->assertNoContent();
    }

    public function createClassifiedAds()
    {
        $this->organization = Organization::factory()->create();
        $this->categoryGroup = CategoryGroup::factory()->create(['organization_id' => $this->organization->id]);

        $this->category = Category::factory()->create([
            'organization_id' => $this->organization->id,
            'category_group_id' => $this->categoryGroup->id
        ]);

        $this->site = Site::factory()->create([
            'organization_id' => $this->organization->id,
            'country_id' => 'FR'
        ]);

        $user = User::factory()->create(['organization_id' => $this->organization->id]);

        $this->classifiedAds = ClassifiedAd::factory()->count(10)->create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'site_id' => $this->site->id,
            'user_id' => $user->id,
        ]);
    }
}
