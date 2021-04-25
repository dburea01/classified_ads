<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PutSortCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [Category::class, $organization]);
        $categories = $this->categoryRepository->index($organization->id);

        // return response()->json($categories, 200);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request, Organization $organization)
    {
        $this->authorize('create', [Category::class, $organization]);
        $category = $this->categoryRepository->insert($organization->id, $request->only(['category_group_id', 'name', 'position', 'state_id']));

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Category $category)
    {
        $this->authorize('view', [Category::class, $organization]);

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, Organization $organization, Category $category)
    {
        $this->authorize('update', [Category::class, $organization, $category]);
        $this->categoryRepository->update($category, $request->only(['category_group_id', 'name', 'position', 'state_id']));

        return new Collection($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Category $category)
    {
        $this->authorize('delete', [Category::class, $organization]);
        $this->categoryRepository->delete($category);

        return response()->noContent();
    }

    public function sortCategories(Organization $organization, CategoryGroup $categoryGroup, PutSortCategoryRequest $request)
    {
        $this->authorize('sort', [Category::class, $organization, $categoryGroup]);

        $this->categoryRepository->sortCategories($organization, $categoryGroup, $request->all());

        return response()->json(['Sort OK'], 200);
    }
}
