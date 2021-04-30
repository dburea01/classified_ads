<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\ClassifiedAd;
use App\Models\Media;
use App\Models\Organization;
use App\Models\Site;
use App\Models\SiteType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    const IMAGE_NAME = 'avatar.jpg';

    public function testPostMediaWithErrors(): void
    {
        $organization = Organization::factory()->create();
        $this->actingAsRole('EMPLOYEE', $organization->id);

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$organization->id}/medias");

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'classified_ad_id',
            'media_file'
        ]);
    }

    public function testPostMediaWithErrorSizeFile(): void
    {
        $classifiedAd = $this->insertClassifiedAd();

        $post = [
            'classified_ad_id' => $classifiedAd->id,
            'media_file' => UploadedFile::fake()->create(self::IMAGE_NAME, 3000)
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias", $post);
        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'media_file'
        ]);
    }

    public function testPostMediaWithErrorTypeFile(): void
    {
        $classifiedAd = $this->insertClassifiedAd();

        $post = [
            'classified_ad_id' => $classifiedAd->id,
            'media_file' => UploadedFile::fake()->create('pdfFile.pdf', 10)
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias", $post);

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'media_file'
        ]);
    }

    public function testPostMediaOk(): void
    {
        Storage::fake('organizations');

        $classifiedAd = $this->insertClassifiedAd();

        $post = [
            'classified_ad_id' => $classifiedAd->id,
            'media_file' => UploadedFile::fake()->create(self::IMAGE_NAME, 10)
        ];

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias", $post);
        $mediaCreated = $response->decodeResponseJson()['data'];

        $organization = Organization::find($classifiedAd->organization_id);
        Storage::disk('organizations')->assertExists("{$organization->container_folder}/medias/{$mediaCreated['storage_name']}");
        $response->assertStatus(201);
    }

    public function testPostMediaMax(): void
    {
        Storage::fake('organizations');

        $classifiedAd = $this->insertClassifiedAd();

        $organization = Organization::find($classifiedAd->organization_id);
        $organization->media_max = 3;
        $organization->save();

        $post = [
            'classified_ad_id' => $classifiedAd->id,
            'media_file' => UploadedFile::fake()->create(self::IMAGE_NAME, 10)
        ];

        for ($i = 1; $i < 4; $i++) {
            $this->json('POST', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias", $post);
        }

        $response = $this->json('POST', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias", $post);

        $response->assertStatus(422);

        $response
        ->assertJsonValidationErrors([
            'media_max'
        ]);
    }

    public function testGetMedias() : void
    {
        $classifiedAd = $this->insertClassifiedAd();
        Media::factory()->count(10)->create(['classified_ad_id' => $classifiedAd->id]);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias?classified_ad_id={$classifiedAd->id}");

        $response->assertStatus(200);
    }

    public function testGetMedia() : void
    {
        $classifiedAd = $this->insertClassifiedAd();
        $media = Media::factory()->create(['classified_ad_id' => $classifiedAd->id]);

        $response = $this->json('GET', $this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias/{$media->id}");

        $response->assertStatus(200);
    }

    public function testDeleteMedia() :void
    {
        Storage::fake('organizations');

        $classifiedAd = $this->insertClassifiedAd();
        $media = Media::factory()->create(['classified_ad_id' => $classifiedAd->id]);

        $response = $this->delete($this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias/{$media->id}");
        $response->assertStatus(204);

        $organization = Organization::find($classifiedAd->organization_id);
        Storage::disk('organizations')->assertMissing("{$organization->container_folder}/medias/{$media->storage_name}");

        $response = $this->delete($this->getUrl() . "/organizations/{$classifiedAd->organization_id}/medias/{$media->id}");
        $response->assertStatus(404);
    }

    public function insertClassifiedAd()
    {
        $organization = Organization::factory()->create();
        $categoryGroup = CategoryGroup::factory()->create(['organization_id' => $organization->id]);
        $category = Category::factory()->create(['organization_id' => $organization->id, 'category_group_id' => $categoryGroup->id]);
        $siteType = SiteType::factory()->create(['organization_id' => $organization->id]);
        $site = Site::factory()->create(['organization_id' => $organization->id, 'site_type_id' => $siteType->id, 'country_id' => 'FR']);

        $user = $this->actingAsRole('EMPLOYEE', $organization->id);

        return ClassifiedAd::factory()->create([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'site_id' => $site->id,
            'ads_status_id' => 'VALIDATED',
            'category_id' => $category->id
        ]);
    }
}
