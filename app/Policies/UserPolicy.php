<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    protected $organizationRoute;

    protected $userRoute;

    public function __construct()
    {
        $this->organizationRoute = request()->route()->parameter('organization');
        $this->userRoute = request()->route()->parameter('user');
    }

    public function before(User $user)
    {
        if ($user->role_id === 'SUPERADMIN') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $this->organizationRoute->id;
    }

    public function view(User $user)
    {
        return  (
            $user->role_id === 'ADMIN' && $user->organization_id === $this->organizationRoute->id && $this->userRoute->organization_id === $user->organization_id
            ) || (
                $user->id === $this->userRoute->id
            );
    }

    public function create(User $user)
    {
        return  $user->role_id === 'ADMIN' && $user->organization_id === $this->organizationRoute->id;
    }

    public function update(User $user)
    {
        return  (
            $user->role_id === 'ADMIN' && $user->organization_id === $this->organizationRoute->id && $this->userRoute->organization_id === $user->organization_id
            ) || (
                $user->id === $this->userRoute->id
            );
    }

    public function delete(User $user)
    {
        return $user->role_id === 'ADMIN' && $user->organization_id === $this->organizationRoute->id && $this->userRoute->organization_id === $user->organization_id;
    }
}
