<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository
{
    public function index(String $organizationId)
    {
        $users = QueryBuilder::for(User::class)
        ->allowedFilters([
            AllowedFilter::partial('first_name'),
            AllowedFilter::partial('last_name'),
            AllowedFilter::partial('email'),
            AllowedFilter::exact('user_state_id')
        ])
        ->allowedFields(['id', 'organization_id', 'first_name', 'last_name', 'email', 'user_state_id'])
        ->allowedSorts('first_name', 'last_name', 'email')
        ->where('organization_id', $organizationId)
        ->defaultSort('last_name');

        return $users->paginate(10)->appends(request()->query());
    }

    public function insert(array $data)
    {
        $user = new User();
        $user->fill($data);
        $user->password = Hash::make($data['password']);
        $user->email_verification_code = Str::random();
        $user->user_state_id = 'CREATED';
        $user->role_id = 'EMPLOYEE';
        $user->save();

        return $user;
    }

    public function update(User $user, array $data)
    {
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function delete(string $organizationId, $userId)
    {
        User::where('organization_id', $organizationId)->where('id', $userId)->delete();
    }

    public function changePassword(User $user, string $password)
    {
        User::where('id', $user->id)
        ->update([
            'password' => Hash::make($password)
        ]);
    }

    public function changeUserState(User $user, string $userStateId)
    {
        User::where('id', $user->id)
        ->update(['user_state_id' => $userStateId]);
    }

    public function validateUser(User $user)
    {
        User::where('id', $user->id)
        ->update([
            'user_state_id' => 'VALIDATED',
            'email_verified_at' => now()
        ]);
    }
}
