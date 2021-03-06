<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\organization;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = $this->organizationRepository->index();

        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\organisation  $organisation
     * @return \Illuminate\Http\Response
     */
    public function show(organization $organization)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\organisation  $organisation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, organization $organization)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\organisation  $organisation
     * @return \Illuminate\Http\Response
     */
    public function destroy(organization $organization)
    {
    }
}
