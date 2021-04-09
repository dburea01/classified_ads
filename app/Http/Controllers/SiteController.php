<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Organization;
use App\Models\Site;
use App\Policies\SitePolicy;
use Illuminate\Http\Request;
use App\Repositories\SiteRepository;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\ErrorHandler\Collecting;

class SiteController extends Controller
{
    private $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [Site::class, $organization]);

        $sites = $this->siteRepository->getAll($organization->id);

        return SiteResource::collection($sites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSiteRequest $request, Organization $organization)
    {
        $this->authorize('create', [Site::class, $organization]);

        $site = $this->siteRepository->insert($organization->id, $request->only(['site_type_id', 'country_id', 'internal_id', 'name', 'address1', 'address2', 'address3', 'zip_code', 'city', 'state_id']));

        return new SiteResource($site);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Site $site)
    {
        $this->authorize('view', [Site::class, $organization, $site]);
        $site = $this->siteRepository->getById($organization->id, $site->id);

        return new SiteResource($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSiteRequest $request, Organization $organization, Site $site)
    {
        $this->authorize('update', [Site::class, $organization, $site]);
        $this->siteRepository->update($site, $request->only(['site_type_id', 'country_id', 'internal_id', 'name', 'address1', 'address2', 'address3', 'zip_code', 'city', 'state_id']));

        return new Collection($site);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Site $site)
    {
        $this->authorize('delete', [Site::class, $organization, $site]);
        $this->siteRepository->delete($site);

        return response()->noContent();
    }
}
