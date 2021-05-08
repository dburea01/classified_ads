<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\DefaultCategoryGroup;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryRepository
{
    public function index(string $organizationId)
    {
        $categoriesTemp = Category::join('category_groups', 'category_groups.id', 'categories.category_group_id')
        ->where('categories.organization_id', $organizationId)->select('categories.*')->with('category_group');

        $categories = QueryBuilder::for($categoriesTemp)
                ->allowedFilters([
                    AllowedFilter::exact('categories.category_group_id'),
                    AllowedFilter::partial('categories.name'),
                    AllowedFilter::exact('categories.state_id'),
                ])
                ->allowedFields(['categories.id', 'categories.name', 'categories.position', 'categories.state_id', 'categories.category_group_id'])
                ->allowedSorts('categories.name', 'categories.position')
                // ->where('category_groups.organization_id', $organizationId)
                // ->allowedIncludes('category_group')
                ->defaultSort('category_groups.position', 'categories.position');

        return $categories->paginate(15);
    }

    public function insert(string $organizationId, array $data) : Category
    {
        $category = new Category();
        $category->organization_id = $organizationId;
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function insertDefaultCategories(string $organizationId) : void
    {
        $defaultCategoryGroups = DefaultCategoryGroup::with('default_categories')->get();

        foreach ($defaultCategoryGroups as $defaultCategoryGroup) {
            $categoryGroup = new CategoryGroup();
            $categoryGroup->organization_id = $organizationId;
            $categoryGroup->position = $defaultCategoryGroup->position;
            $categoryGroup->name = $defaultCategoryGroup->name;
            $categoryGroup->save();

            foreach ($defaultCategoryGroup->default_categories as $defaultCategory) {
                $category = new Category();
                $category->organization_id = $organizationId;
                $category->category_group_id = $categoryGroup->id;
                $category->position = $defaultCategory->position;
                $category->name = $defaultCategory->name;
                $category->save();
            }
        }
    }

    public function update(Category $category, array $data) : Category
    {
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function delete(Category $category) : void
    {
        $category->delete();
    }

    public function sortCategories(Organization $organization, CategoryGroup $categoryGroup, array $ids) : void
    {
        foreach ($ids as $key => $id) {
            $category = Category::where('id', $id)
            ->where('organization_id', $organization->id)
            ->where('category_group_id', $categoryGroup->id)
            ->first();

            $category->position = $key;
            $category->save();
        }
    }
}
