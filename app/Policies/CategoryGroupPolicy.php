<?php

namespace App\Policies;

use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Models\SiteType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryGroupPolicy
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
        return  $user->organization_id === $organization->id;
    }

    public function view(User $user, Organization $organization, CategoryGroup $categoryGroup)
    {
        return  $user->organization_id === $organization->id && $categoryGroup->organization_id === $organization->id;
    }

    public function create(User $user, Organization $organization)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $organization->id;
    }

    public function update(User $user, Organization $organization, CategoryGroup $categoryGroup)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $organization->id && $categoryGroup->organization_id === $organization->id;
    }

    public function delete(User $user, Organization $organization, CategoryGroup $categoryGroup)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $organization->id && $categoryGroup->organization_id === $organization->id;
    }
}
