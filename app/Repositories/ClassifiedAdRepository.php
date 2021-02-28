<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ClassifiedAd;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClassifiedAdRepository
{
    public function getAll(string $organizationId)
    {
        // return ClassifiedAd::with('organization')->where('organization_id', $organizationId)->paginate(10);

        $classifiedAds = QueryBuilder::for(ClassifiedAd::class)
                ->allowedFilters([
                    AllowedFilter::partial('title'),
                    AllowedFilter::partial('description'),
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::exact('user_id'),
                    AllowedFilter::exact('site_id'),
                    AllowedFilter::exact('ad_status_id')
                ])
                ->allowedFields(['id', 'organization_id', 'title', 'description', 'category_id', 'created_at',
                    'category.id', 'category.name'])
                ->allowedIncludes(['category'])
                // ->with('category')
                ->where('organization_id', $organizationId)
                ->defaultSort('created_at');

        return $classifiedAds->paginate(10);
    }

    public function getById(string $id)
    {
        $song = QueryBuilder::for(Song::class)
                ->allowedFields(['songs.id', 'songs.name', 'songs.duration', 'songs.position', 'songs.album_id',
                    'albums.id', 'albums.name'])
                ->allowedIncludes(['album', 'lyrics'])
                ->find($id);

        return $song;
    }
}
