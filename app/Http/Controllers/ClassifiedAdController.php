<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassifiedAdResource;
use App\Models\ClassifiedAd;
use App\Repositories\ClassifiedAdRepository;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class ClassifiedAdController extends Controller
{
    private $classifiedAdRepository;

    private $organizationRepository;

    public function __construct(Request $request, ClassifiedAdRepository $classifiedAdRepository)
    {
        $this->classifiedAdRepository = $classifiedAdRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $classifiedAds = $this->classifiedAdRepository->getAll($this->organizationId);

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
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassifiedAd  $classifiedAd
     * @return \Illuminate\Http\Response
     */
    public function show(ClassifiedAd $classifiedAd)
    {
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
