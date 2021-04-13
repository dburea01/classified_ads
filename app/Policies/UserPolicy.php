<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return  $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    public function view(User $user, Organization $organization, User $userToDisplay)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $organization->id && $userToDisplay->organization_id === $organization->id;
    }

    public function create(User $user, Organization $organization)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    public function update(User $user, Organization $organization, User $userToUpdate)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $organization->id && $userToUpdate->organization_id === $organization->id;
    }

    public function delete(User $user, Organization $organization)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    public function restore(User $user, Site $site)
    {
    }

    public function forceDelete(User $user, Site $site)
    {
    }
}
