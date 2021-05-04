<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassifiedAdRequest;
use App\Http\Resources\ClassifiedAdResource;
use App\Models\ClassifiedAd;
use App\Models\Organization;
use App\Repositories\ClassifiedAdRepository;
use App\Http\Controllers\Controller;

class ClassifiedAdController extends Controller
{
    private $classifiedAdRepository;

    public function __construct(ClassifiedAdRepository $classifiedAdRepository)
    {
        $this->classifiedAdRepository = $classifiedAdRepository;
        $this->authorizeResource(ClassifiedAd::class);
    }

    public function index(Organization $organization)
    {
        try {
            $classifiedAds = $this->classifiedAdRepository->getAll($organization->id);

            return ClassifiedAdResource::collection($classifiedAds);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Organization $organization, StoreClassifiedAdRequest $request)
    {
        try {
            $classifiedAd = $this->classifiedAdRepository->insert($organization->id, $request->only(['category_id', 'site_id', 'title', 'description', 'price', 'currency_id']));

            return new ClassifiedAdResource($classifiedAd);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show(Organization $organization, ClassifiedAd $classifiedAd)
    {
        $classifiedAd = $this->classifiedAdRepository->getById($classifiedAd->id);

        return new ClassifiedAdResource($classifiedAd);
    }

    public function update(Organization $organization, ClassifiedAd $classifiedAd, StoreClassifiedAdRequest $request)
    {
        $classifiedAd = $this->classifiedAdRepository->update($classifiedAd, $request->only(['category_id', 'site_id', 'ads_status_id', 'title', 'description', 'price', 'currency_id']));

        return new ClassifiedAdResource($classifiedAd);
    }

    public function destroy(Organization $organization, ClassifiedAd $classifiedAd)
    {
        try {
            $this->classifiedAdRepository->delete($classifiedAd);

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
