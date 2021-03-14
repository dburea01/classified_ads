<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CategoryGroup;
use App\Models\SiteType;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryGroupRepository
{
    public function index(string $organizationId)
    {
        // $categoryGroups = CategoryGroup::where('organization_id', $organizationId)->orderBy('position')->get();

        $categoryGroups = QueryBuilder::for(CategoryGroup::class)
                ->allowedFilters([
                    AllowedFilter::exact('state_id')
                ])
                ->allowedFields(['id', 'organization_id', 'name', 'position'])
                ->allowedSorts('name', 'position')
                ->where('organization_id', $organizationId)
                ->defaultSort('position');

        return $categoryGroups->get();
    }

    public function insert(string $organizationId, array $data) : CategoryGroup
    {
        $categoryGroup = new CategoryGroup();
        $categoryGroup->organization_id = $organizationId;
        $categoryGroup->fill($data);
        $categoryGroup->save();

        return $categoryGroup;
    }

    public function update(CategoryGroup $categoryGroup, array $data) : CategoryGroup
    {
        $categoryGroup->fill($data);
        $categoryGroup->save();

        return $categoryGroup;
    }

    public function delete(CategoryGroup $categoryGroup) : void
    {
        $categoryGroup->delete();
    }
}
