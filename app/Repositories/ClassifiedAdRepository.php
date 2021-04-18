<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ClassifiedAd;
use Illuminate\Support\Facades\Auth;
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
                ->allowedFields(['id', 'organization_id', 'site_id', 'title', 'description', 'category_id', 'created_at', 'price', 'currency_id',
                    'category.id', 'category.name'])
                ->allowedIncludes(['category', 'site', 'currency', 'medias'])
                ->where('organization_id', $organizationId)
                ->defaultSort('-created_at');

        return $classifiedAds->paginate(20);
    }

    public function getById(string $id)
    {
        $classifiedAd = QueryBuilder::for(ClassifiedAd::class)
                ->allowedFields(['id', 'organization_id', 'site_id', 'title', 'description', 'category_id', 'created_at', 'price', 'currency_id',
                    'category.id', 'category.name', 'site.id', 'site.name'])
                ->allowedIncludes(['category', 'site', 'currency'])
                ->find($id);

        return $classifiedAd;
    }

    public function insert(string $organizationId, array $data)
    {
        $classifiedAd = new ClassifiedAd();
        $classifiedAd->organization_id = $organizationId;
        $classifiedAd->user_id = Auth::user()->id;
        $classifiedAd->ads_status_id = 'CREATED';
        $classifiedAd->fill($data);
        $classifiedAd->save();

        return $classifiedAd;
    }

    public function update(ClassifiedAd $classifiedAd, array $data) : ClassifiedAd
    {
        $classifiedAd->fill($data);
        $classifiedAd->save();

        return $classifiedAd;
    }

    public function delete(ClassifiedAd $classifiedAd)
    {
        $classifiedAd->delete();
    }
}
