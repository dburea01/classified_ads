<?php

namespace App\Policies;

use App\Models\ClassifiedAd;
use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role_id === 'SUPERADMIN') {
            return true;
        }
    }

    public function viewAny(User $user, Organization $organization)
    {
        return $user->organization_id === $organization->id;
    }

    public function view(User $user, Organization $organization)
    {
        return $user->organization_id === $organization->id;
    }

    public function create(User $user, string $organizationId, String $classifiedAdId)
    {
        $classifiedAd = ClassifiedAd::find($classifiedAdId);

        return ($user->role_id === 'ADMIN' && $user->organization_id === $organizationId)
                ||
                $user->id === $classifiedAd->user_id;
    }

    public function update(User $user, Organization $organization)
    {
    }

    public function delete(User $user, ClassifiedAd $classifiedAd)
    {
        return $user->id === $classifiedAd->id;
    }

    public function restore(User $user, Site $site)
    {
    }

    public function forceDelete(User $user, Site $site)
    {
    }
}
