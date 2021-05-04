<?php

namespace App\Http\Controllers;

use App\Http\Requests\PutSortCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->authorizeResource(Category::class);
    }

    public function index(Organization $organization)
    {
        $categories = $this->categoryRepository->index($organization->id);

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request, Organization $organization)
    {
        $category = $this->categoryRepository->insert($organization->id, $request->only(['category_group_id', 'name', 'position', 'state_id']));

        return new CategoryResource($category);
    }

    public function show(Organization $organization, Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(StoreCategoryRequest $request, Organization $organization, Category $category)
    {
        $this->categoryRepository->update($category, $request->only(['category_group_id', 'name', 'position', 'state_id']));

        return new Collection($category);
    }

    public function destroy(Organization $organization, Category $category)
    {
        $this->categoryRepository->delete($category);

        return response()->noContent();
    }

    public function sortCategories(Organization $organization, CategoryGroup $categoryGroup, PutSortCategoryRequest $request)
    {
        $this->authorize('sort', Category::class);

        $this->categoryRepository->sortCategories($organization, $categoryGroup, $request->all());

        return response()->json(['Sort OK'], 200);
    }
}
