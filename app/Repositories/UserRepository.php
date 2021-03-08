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
    public function insert(array $data)
    {
        $user = new User();
        $user->fill($data);
        $user->password = Hash::make($data['password']);
        $user->email_verification_code = Str::random();
        $user->state_id = 'CREATED';
        $user->role_id = 'EMPLOYEE';
        $user->save();

        return $user;
    }

    public function changePassword(User $user, string $password)
    {
        User::where('id', $user->id)
        ->update([
            'password' => Hash::make($password)
        ]);
    }

    public function changeUserState(User $user, string $stateId)
    {
        User::where('id', $user->id)
        ->update(['state_id' => $stateId]);
    }

    public function validateUser(User $user)
    {
        User::where('id', $user->id)
        ->update([
            'state_id' => 'VALIDATED',
            'email_verified_at' => now()
        ]);
    }
}
