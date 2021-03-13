<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role_id === 'SUPERADMIN') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Organization $organization)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return mixed
     */
    public function view(User $user, Site $site)
    {
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return mixed
     */
    public function restore(User $user, Site $site)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Site  $site
     * @return mixed
     */
    public function forceDelete(User $user, Site $site)
    {
    }
}