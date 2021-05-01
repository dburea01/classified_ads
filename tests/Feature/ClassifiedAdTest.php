<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\ClassifiedAd;
use App\Models\Organization;
use App\Models\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ClassifiedAdTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    private $organization;

    private $site;

    private $categoryGroup;

    private $category;

    public function testPostClassifiedAdWithErrors(): void
    {
        $organization = Organization::factory()->create();

        $this->actingAsRole('EMPLOYEE', $organization->id);

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/classified-ads");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'category_id',
            'site_id',
            'title',
            'price',
            'currency_id',
            // 'ads_status_id'
        ]);
    }

    public function testPostClassifiedAdOk(): void
    {
        $this->createOrganization();

        $this->actingAsRole('EMPLOYEE', $this->organization->id);

        $classifiedAdToCreate = [
            'category_id' => $this->category->id,
            'site_id' => $this->site->id,
            'title' => 'title test',
            'description' => 'description test',
            'price' => '123',
            'currency_id' => 'EUR',
            // 'ads_status_id' => 'CREATED',
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads", $classifiedAdToCreate);

        $response->assertStatus(201);
    }

    public function testPutClassifiedAdWithErrors()
    {
        $this->createOrganization();
        $user = $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            //'ads_status_id' => 'VALIDATED',
            'title' => 'title',
            'description' => 'description',
            'price' => 123,
            'currency_id' => 'EUR'
        ]);

        $classifiedAdToUpdate = [
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            //'ads_status_id' => 'FAKE',
            'title' => 'title',
            'description' => 'description',
            'price' => 'zer',
            'currency_id' => 'TOTO'
        ];

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$classifiedAd->id}", $classifiedAdToUpdate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'price',
            'currency_id'
        ]);
    }

    public function testPutClassifiedAdOk()
    {
        $this->createOrganization();

        $user = $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            'ads_status_id' => 'VALIDATED',
            'title' => 'title',
            'description' => 'description',
            'price' => 123,
            'currency_id' => 'EUR'
        ]);

        $classifiedAdToUpdate = [
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            'ads_status_id' => 'CREATED',
            'title' => 'title',
            'description' => 'description',
            'price' => '12345',
            'currency_id' => 'EUR'
        ];

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$classifiedAd->id}", $classifiedAdToUpdate);

        $response->assertStatus(200);
    }

    public function testGetClassifiedAds() : void
    {
        $this->createOrganization();

        $user = $this->actingAsRole('EMPLOYEE', $this->organization->id);
        ClassifiedAd::factory()->count(10)->create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            'ads_status_id' => 'VALIDATED'
        ]);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads");

        $response->assertStatus(200);
    }

    public function testGetClassifiedAd() : void
    {
        $this->createOrganization();
        $user = $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            'ads_status_id' => 'VALIDATED'
        ]);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$classifiedAd->id}");

        $response->assertStatus(200);
    }

    public function testDeleteClassifiedAd() :void
    {
        $this->createOrganization();
        $user = $this->actingAsRole('EMPLOYEE', $this->organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $this->organization->id,
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'site_id' => $this->site->id,
            'ads_status_id' => 'VALIDATED'
        ]);

        $response = $this->delete($this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$classifiedAd->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$this->organization->id}/classified-ads/{$classifiedAd->id}");

        $response->assertStatus(404);
    }

    public function createOrganization(): void
    {
        $this->organization = Organization::factory()->create();
        $this->site = Site::factory()->create(['organization_id' => $this->organization->id, 'country_id' => 'FR']);
        CategoryGroup::factory()->create(['organization_id' => $this->organization->id]);
        $this->category = Category::factory()->create(['organization_id' => $this->organization->id]);
    }
}
