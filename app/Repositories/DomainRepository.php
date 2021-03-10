<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Domain;

class DomainRepository
{
    public function index(string $organizationId)
    {
        $domains = Domain::where('organization_id', $organizationId)->orderBy('name')->get();

        return $domains;
    }

    public function insertDomain(string $organizationId, array $data) : Domain
    {
        $domain = new Domain();
        $domain->organization_id = $organizationId;
        $domain->fill($data);
        $domain->save();

        return $domain;
    }

    public function updateDomain(Domain $domain, array $data) : Domain
    {
        $domain->fill($data);
        $domain->save();

        return $domain;
    }

    public function deleteDomain(Domain $domain) : void
    {
        $domain->delete();
    }
}
