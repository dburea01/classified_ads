<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassifiedAdRequest;
use App\Http\Resources\ClassifiedAdResource;
use App\Models\ClassifiedAd;
use App\Models\Organization;
use App\Policies\ClassifiedAdPolicy;
use App\Repositories\ClassifiedAdRepository;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class ClassifiedAdController extends Controller
{
    private $classifiedAdRepository;

    private $organizationRepository;

    public function __construct(ClassifiedAdRepository $classifiedAdRepository)
    {
        $this->classifiedAdRepository = $classifiedAdRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [ClassifiedAd::class, $organization]);

        try {
            $classifiedAds = $this->classifiedAdRepository->getAll($organization->id);

            return ClassifiedAdResource::collection($classifiedAds);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Organization $organization, StoreClassifiedAdRequest $request)
    {
        $this->authorize('create', [ClassifiedAd::class, $organization]);

        $classifiedAd = $this->classifiedAdRepository->insert($organization->id, $request->only(['category_id', 'site_id', 'ads_status_id', 'title', 'description', 'price', 'currency_id']));

        return new ClassifiedAdResource($classifiedAd);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, ClassifiedAd $classifiedAd)
    {
        $this->authorize('view', [ClassifiedAd::class, $organization]);

        $classifiedAd = $this->classifiedAdRepository->getById($classifiedAd->id);

        return new ClassifiedAdResource($classifiedAd);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassifiedAd $classifiedAd)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassifiedAd $classifiedAd)
    {
    }
}
