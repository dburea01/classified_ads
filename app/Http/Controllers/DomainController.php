<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Resources\DomainResource;
use App\Models\Domain;
use App\Models\Organization;
use App\Repositories\DomainRepository;
use App\Http\Controllers\Controller;

class DomainController extends Controller
{
    private $domainRepository;

    public function __construct(DomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $domains = $this->domainRepository->index($organization->id);

        return DomainResource::collection($domains);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Organization $organization, StoreDomainRequest $request)
    {
        $domain = $this->domainRepository->insertDomain($organization->id, $request->only(['name']));

        return new DomainResource($domain);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Domain $domain)
    {
        return new DomainResource($domain);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDomainRequest $request, Organization $organization, Domain $domain)
    {
        $this->domainRepository->updateDomain($domain, $request->only(['name']));

        return (new DomainResource($domain))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Domain $domain)
    {
        $this->domainRepository->deleteDomain($domain);

        return response()->noContent();
    }
}
