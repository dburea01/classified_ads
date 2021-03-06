<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Organization;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrganizationRepository
{
    public function index()
    {
        $organizations = QueryBuilder::for(Organization::class)
        ->allowedFilters([
            AllowedFilter::partial('name')
        ])
        ->defaultSort('name');

        return $organizations->paginate(10);
    }
}
