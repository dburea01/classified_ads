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
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id]);

        $this->actingAsRole('EMPLOYEE', $organization->id);

        $classifiedAdToCreate = [
            'category_id' => $category->id,
            'site_id' => $site->id,
            'title' => 'title test',
            'description' => 'description test',
            'price' => '123',
            'currency_id' => 'EUR',
            // 'ads_status_id' => 'CREATED',
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/classified-ads", $classifiedAdToCreate);

        $response->assertStatus(201);
    }

    public function testPutClassifiedAdWithErrors()
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id]);

        $user = $this->actingAsRole('EMPLOYEE', $organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            //'ads_status_id' => 'VALIDATED',
            'title' => 'title',
            'description' => 'description',
            'price' => 123,
            'currency_id' => 'EUR'
        ]);

        $classifiedAdToUpdate = [
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            //'ads_status_id' => 'FAKE',
            'title' => 'title',
            'description' => 'description',
            'price' => 'zer',
            'currency_id' => 'TOTO'
        ];

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/classified-ads/{$classifiedAd->id}", $classifiedAdToUpdate);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'price',
            'currency_id'
        ]);
    }

    public function testPutClassifiedAdOk()
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id]);

        $user = $this->actingAsRole('EMPLOYEE', $organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            'ads_status_id' => 'VALIDATED',
            'title' => 'title',
            'description' => 'description',
            'price' => 123,
            'currency_id' => 'EUR'
        ]);

        $classifiedAdToUpdate = [
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            'ads_status_id' => 'CREATED',
            'title' => 'title',
            'description' => 'description',
            'price' => '12345',
            'currency_id' => 'EUR'
        ];

        $response = $this->json('PUT', $this->getUrl() . "/organizations/{$organization->id}/classified-ads/{$classifiedAd->id}", $classifiedAdToUpdate);

        $response->assertStatus(200);
    }

    public function testGetClassifiedAds() : void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id]);

        $user = $this->actingAsRole('EMPLOYEE', $organization->id);
        $classifiedAd = ClassifiedAd::factory()->count(10)->create([
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            'ads_status_id' => 'VALIDATED'
        ]);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/classified-ads");

        $response->assertStatus(200);
    }

    public function testGetClassifiedAd() : void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id]);

        $user = $this->actingAsRole('EMPLOYEE', $organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            'ads_status_id' => 'VALIDATED'
        ]);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$organization->id}/classified-ads/{$classifiedAd->id}");

        $response->assertStatus(200);
    }

    public function testDeleteClassifiedAd() :void
    {
        $organization = Organization::factory()->create();
        $site = Site::factory()->create(['organization_id' => $organization->id, 'country_id' => 'FR']);
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id]);

        $user = $this->actingAsRole('EMPLOYEE', $organization->id);
        $classifiedAd = ClassifiedAd::factory()->create([
            'organization_id' => $organization->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'site_id' => $site->id,
            'ads_status_id' => 'VALIDATED'
        ]);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/classified-ads/{$classifiedAd->id}");

        $response->assertStatus(204);

        $response = $this->delete($this->getUrl() . "/organizations/{$organization->id}/classified-ads/{$classifiedAd->id}");

        $response->assertStatus(404);
    }
}
