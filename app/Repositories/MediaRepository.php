<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Domain;
use App\Models\Media;

class MediaRepository
{
    public function index(string $organizationId)
    {
        $domains = Domain::where('organization_id', $organizationId)->orderBy('name')->get();

        return $domains;
    }

    public function insert(array $data, string $mediaName) : Media
    {
        $media = new Media();
        $media->fill($data);
        $media->name = $mediaName;
        $media->save();

        return $media;
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
