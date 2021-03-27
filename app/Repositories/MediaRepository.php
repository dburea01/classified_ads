<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ClassifiedAd;
use App\Models\Domain;
use App\Models\Media;
use App\Models\Organization;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MediaRepository
{
    public function index(Organization $organization, string $classifiedAdId)
    {
        $medias = Media::where('classified_ad_id', $classifiedAdId)->orderBy('created_at');

        return $medias->paginate(10);
    }

    public function insert(array $data) : Media
    {
        $media = new Media();
        $media->fill($data);
        $media->save();

        return $media;
    }

    public function update(Media $media, array $data) : Media
    {
        $media->fill($data);
        $media->save();

        return $media;
    }

    public function delete(Media $media) : void
    {
        $media->delete();
    }
}
