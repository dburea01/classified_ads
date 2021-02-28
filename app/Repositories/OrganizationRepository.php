<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Organization;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrganizationRepository
{
    public function getOrganizationFromRequest(string $codeOrganization)
    {
        return Organization::where('code', $codeOrganization)->first()->id;
    }
}
