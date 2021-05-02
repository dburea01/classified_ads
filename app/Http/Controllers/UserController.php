<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Organization;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->authorizeResource(User::class);
    }

    public function index(Organization $organization)
    {
        $users = $this->userRepository->index($organization->id);

        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        //do nothing, this method should not be called. To create an user, it must be made by the user himself.
    }

    public function show(Organization $organization, User $user)
    {
        return new UserResource($user);
    }

    public function update(StoreUserRequest $request, Organization $organization, User $user)
    {
        if (in_array(Auth::user()->role_id, ['ADMIN', 'SUPERADMIN'])) {
            $dataToUpdate = $request->only(['first_name', 'last_name', 'role_id', 'user_state_id']);
        } else {
            $dataToUpdate = $request->only(['first_name', 'last_name']);
        }
        $this->userRepository->update($user, $dataToUpdate);

        return new Collection($user);
    }

    public function destroy(Organization $organization, User $user)
    {
        $this->userRepository->delete($organization->id, $user->id);

        return response()->noContent();
    }
}
