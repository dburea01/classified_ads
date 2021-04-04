<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SiteType;

class SiteTypeRepository
{
    public function index(string $organizationId)
    {
        $siteTypes = SiteType::where('organization_id', $organizationId)->withCount('sites')->orderBy('name')->get();

        return $siteTypes;
    }

    public function insertSiteType(string $organizationId, array $data) : SiteType
    {
        $siteType = new SiteType();
        $siteType->organization_id = $organizationId;
        $siteType->fill($data);
        $siteType->save();

        return $siteType;
    }

    public function updateSiteType(SiteType $siteType, array $data) : SiteType
    {
        $siteType->fill($data);
        $siteType->save();

        return $siteType;
    }

    public function deleteSiteType(SiteType $siteType) : void
    {
        $siteType->delete();
    }
}
