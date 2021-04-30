<?php

namespace App\Policies;

use App\Models\ClassifiedAd;
use App\Models\Media;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
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

    public function delete(User $user, Organization $organization, Media $media)
    {
        $classifiedAd = ClassifiedAd::find($media->classified_ad_id);

        return ($user->role_id === 'ADMIN' && $user->organization_id === $organization->id)
                ||
                $user->id === $classifiedAd->user_id;
    }
}
