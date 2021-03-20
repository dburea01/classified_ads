<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryGroup;
use App\Http\Requests\StoreCategoryGroupRequest;
use App\Http\Resources\CategoryGroupResource;
use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Repositories\CategoryGroupRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use PhpParser\ErrorHandler\Collecting;

class CategoryGroupController extends Controller
{
    private $categoryGroupRepository;

    public function __construct(CategoryGroupRepository $categoryGroupRepository)
    {
        $this->categoryGroupRepository = $categoryGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [CategoryGroup::class, $organization]);

        $categoryGroups = $this->categoryGroupRepository->index($organization->id);

        return CategoryGroupResource::collection($categoryGroups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryGroupRequest $request, Organization $organization)
    {
        $this->authorize('create', [CategoryGroup::class, $organization]);

        $categoryGroup = $this->categoryGroupRepository->insert($organization->id, $request->only(['name', 'position', 'state_id']));

        return new CategoryGroupResource($categoryGroup);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryGroup  $categoryGroup
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, CategoryGroup $categoryGroup)
    {
        $this->authorize('view', [CategoryGroup::class, $organization]);

        return new CategoryGroupResource($categoryGroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryGroup  $categoryGroup
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryGroupRequest $request, Organization $organization, CategoryGroup $categoryGroup)
    {
        $this->authorize('update', [CategoryGroup::class, $organization]);

        $this->categoryGroupRepository->update($categoryGroup, $request->only(['name', 'position', 'state_id']));

        return new collection($categoryGroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryGroup  $categoryGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, CategoryGroup $categoryGroup)
    {
        $this->authorize('delete', [CategoryGroup::class, $organization]);

        $this->categoryGroupRepository->delete($categoryGroup);

        return response()->noContent();
    }
}
