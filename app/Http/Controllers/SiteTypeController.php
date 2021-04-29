<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteTypeRequest;
use App\Http\Resources\SiteTypeResource;
use App\Models\Organization;
use App\Models\SiteType;
use App\Repositories\SiteTypeRepository;
use App\Http\Controllers\Controller;

class SiteTypeController extends Controller
{
    private $siteTypeRepository;

    public function __construct(SiteTypeRepository $siteTypeRepository)
    {
        $this->siteTypeRepository = $siteTypeRepository;
        $this->authorizeResource(SiteType::class, 'site_type');
    }

    public function index(Organization $organization)
    {
        $siteTypes = $this->siteTypeRepository->index($organization->id);

        return SiteTypeResource::collection($siteTypes);
    }

    public function store(StoreSiteTypeRequest $request, Organization $organization)
    {
        $siteType = $this->siteTypeRepository->insertSiteType($organization->id, $request->only(['name', 'state_id']));

        return new SiteTypeResource($siteType);
    }

    public function show(Organization $organization, SiteType $siteType)
    {
        return new SiteTypeResource($siteType);
    }

    public function update(StoreSiteTypeRequest $request, Organization $organization, SiteType $siteType)
    {
        $this->siteTypeRepository->updateSiteType($siteType, $request->only(['name', 'state_id']));

        return new SiteTypeResource($siteType);
    }

    public function destroy(Organization $organization, SiteType $siteType)
    {
        $this->siteTypeRepository->deleteSiteType($siteType);

        return response()->noContent();
    }
}
