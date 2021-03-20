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

        $categories = CategoryGroup::join('categories', 'category_groups.id', 'categories.category_group_id')
        ->where('category_groups.organization_id', $organizationId);
        // ->get();

        // return $categories;

        $categories = QueryBuilder::for($categories)
                ->allowedFilters([
                    AllowedFilter::exact('category_groups.id')
                ])
                ->allowedFields(['categories.id', 'categories.name', 'categories.position', 'categories.state_id'])
                ->allowedSorts('categories.name', 'categories.position')
                ->defaultSort('categories.position');

        return $categories->get();
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
