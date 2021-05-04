<?php

namespace App\Policies;

use App\Models\CategoryGroup;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryGroupPolicy
{
    use HandlesAuthorization;

    private $organization;

    public function __construct()
    {
        $this->organization = request()->route()->parameter('organization');
    }

    public function before(User $user)
    {
        if ($user->role_id === 'SUPERADMIN') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return  $user->organization_id === $this->organization->id;
    }

    public function view(User $user)
    {
        return  $user->organization_id === $this->organization->id;
    }

    public function create(User $user)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $this->organization->id;
    }

    public function update(User $user)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $this->organization->id;
    }

    public function delete(User $user)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $this->organization->id;
    }
}
