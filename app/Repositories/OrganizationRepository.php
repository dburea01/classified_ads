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

    public function insertOrganisation(array $data) : Organization
    {
        $organization = new Organization();
        $organization->fill($data);
        $organization->save();

        return $organization;
    }

    public function updateOrganization(Organization $organization, array $data) : Organization
    {
        $organization->fill($data);
        $organization->save();

        return $organization;
    }

    public function deleteOrganization(Organization $organization) : void
    {
        $organization->delete();
    }
}
