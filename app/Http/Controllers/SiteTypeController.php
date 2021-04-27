<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteTypeRequest;
use App\Http\Resources\SiteTypeResource;
use App\Models\Organization;
use App\Models\SiteType;
use App\Policies\SiteTypePolicy;
use App\Repositories\SiteTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteTypeController extends Controller
{
    private $siteTypeRepository;

    public function __construct(SiteTypeRepository $siteTypeRepository)
    {
        $this->siteTypeRepository = $siteTypeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [SiteType::class, $organization]);
        $siteTypes = $this->siteTypeRepository->index($organization->id);

        return SiteTypeResource::collection($siteTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSiteTypeRequest $request, Organization $organization)
    {
        $this->authorize('create', [SiteType::class, $organization]);
        $siteType = $this->siteTypeRepository->insertSiteType($organization->id, $request->only(['name', 'state_id']));

        return new SiteTypeResource($siteType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SiteType  $siteType
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, SiteType $siteType)
    {
        $this->authorize('view', [SiteType::class, $organization]);

        return new SiteTypeResource($siteType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SiteType  $siteType
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSiteTypeRequest $request, Organization $organization, SiteType $siteType)
    {
        $this->authorize('update', [SiteType::class, $organization]);
        $this->siteTypeRepository->updateSiteType($siteType, $request->only(['name', 'state_id']));

        return new SiteTypeResource($siteType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SiteType  $siteType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, SiteType $siteType)
    {
        $this->authorize('delete', [SiteType::class, $organization]);
        $this->siteTypeRepository->deleteSiteType($siteType);

        return response()->noContent();
    }
}
