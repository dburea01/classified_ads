<?php

namespace App\Http\Controllers;

use App\Http\Requests\PutSortGroupCategoryRequest;
use App\Http\Requests\StoreCategoryGroup;
use App\Http\Requests\StoreCategoryGroupRequest;
use App\Http\Resources\CategoryGroupResource;
use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Repositories\CategoryGroupRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;

class CategoryGroupController extends Controller
{
    private $categoryGroupRepository;

    public function __construct(CategoryGroupRepository $categoryGroupRepository)
    {
        $this->categoryGroupRepository = $categoryGroupRepository;
        $this->authorizeResource(CategoryGroup::class);
    }

    public function index(Organization $organization)
    {
        $categoryGroups = $this->categoryGroupRepository->index($organization->id);

        return CategoryGroupResource::collection($categoryGroups);
    }

    public function store(StoreCategoryGroupRequest $request, Organization $organization)
    {
        $categoryGroup = $this->categoryGroupRepository->insert($organization->id, $request->only(['name', 'position', 'state_id']));

        return new CategoryGroupResource($categoryGroup);
    }

    public function show(Organization $organization, CategoryGroup $categoryGroup)
    {
        return new CategoryGroupResource($categoryGroup);
    }

    public function update(StoreCategoryGroupRequest $request, Organization $organization, CategoryGroup $categoryGroup)
    {
        $this->categoryGroupRepository->update($categoryGroup, $request->only(['name', 'position', 'state_id']));

        return new collection($categoryGroup);
    }

    public function destroy(Organization $organization, CategoryGroup $categoryGroup)
    {
        $this->categoryGroupRepository->delete($categoryGroup);

        return response()->noContent();
    }

    public function sortCategoryGroups(Organization $organization, PutSortGroupCategoryRequest $request)
    {
        $this->categoryGroupRepository->sortCategoryGroups($organization, $request->all());

        return response()->json(['Sort OK'], 200);
    }
}
