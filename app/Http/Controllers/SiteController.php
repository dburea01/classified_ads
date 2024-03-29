<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Organization;
use App\Models\Site;
use App\Repositories\SiteRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    private $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->authorizeResource(Site::class, 'site');
    }

    public function index(Organization $organization)
    {
        $sites = $this->siteRepository->getAll($organization->id);

        return SiteResource::collection($sites);
    }

    public function store(StoreSiteRequest $request, Organization $organization)
    {
        $site = $this->siteRepository->insert($organization->id, $request->only(['site_type_id', 'country_id', 'internal_id', 'name', 'address1', 'address2', 'address3', 'zip_code', 'city', 'state_id']));

        return new SiteResource($site);
    }

    public function show(Organization $organization, Site $site)
    {
        $site = $this->siteRepository->getById($organization->id, $site->id);

        return new SiteResource($site);
    }

    public function update(StoreSiteRequest $request, Organization $organization, Site $site)
    {
        $this->siteRepository->update($site, $request->only(['site_type_id', 'country_id', 'internal_id', 'name', 'address1', 'address2', 'address3', 'zip_code', 'city', 'state_id']));

        return new Collection($site);
    }

    public function destroy(Organization $organization, Site $site)
    {
        $this->siteRepository->delete($site);

        return response()->noContent();
    }
}
