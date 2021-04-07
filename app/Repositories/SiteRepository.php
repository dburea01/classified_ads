<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Site;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SiteRepository
{
    public function getAll(string $organizationId)
    {
        // return ClassifiedAd::with('organization')->where('organization_id', $organizationId)->paginate(10);

        $sites = QueryBuilder::for(Site::class)
                ->allowedFilters([
                    AllowedFilter::partial('name'),
                    AllowedFilter::partial('city'),
                    AllowedFilter::exact('site_type_id'),
                    AllowedFilter::exact('state_id'),
                    AllowedFilter::exact('country_id')
                ])
                ->allowedFields(['id', 'internal_id', 'organization_id', 'site_type_id', 'name', 'city', 'address1', 'address2', 'address3', 'zip_code'])
                ->where('organization_id', $organizationId)
                ->with('siteType')
                ->defaultSort('internal_id');

        return $sites->paginate(10)->appends(request()->query());
    }

    public function getById(string $organizationId, string $siteId)
    {
        $site = QueryBuilder::for(Site::class)
        ->allowedFields(['id', 'internal_id', 'organization_id', 'site_type_id', 'name', 'city', 'address1', 'address2', 'address3', 'zip_code'])
        ->where('organization_id', $organizationId)
        ->where('id', $siteId)
        ->first();

        return $site;
    }

    public function insert(string $organizationId, array $data) : Site
    {
        $site = new Site();
        $site->organization_id = $organizationId;
        $site->fill($data);
        $site->save();

        return $site;
    }

    public function update(Site $site, array $data) : Site
    {
        $site->fill($data);
        $site->save();

        return $site;
    }

    public function delete(Site $site)
    {
        $site->delete();
    }
}
