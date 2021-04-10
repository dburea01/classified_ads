<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryRepository
{
    public function index(string $organizationId)
    {
        // $categories = Organization::where('id', $organizationId)->with('categories')->get();
        /*
        $categories = DB::table('category_groups as cg')
        ->join('categories as c', 'cg.id', 'c.category_group_id')
        ->where('cg.organization_id', $organizationId)
        ->get();
        */

        // $categoriesTemp = CategoryGroup::join('categories', 'category_groups.id', 'categories.category_group_id')
        // ->where('category_groups.organization_id', $organizationId);
        // ->get();
        $categoriesTemp = Category::join('category_groups', 'category_groups.id', 'categories.category_group_id')
        ->where('categories.organization_id', $organizationId)->select('categories.*')->with('category_group');

        // return $categoriesTemp;
        // return $categories;

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
}
