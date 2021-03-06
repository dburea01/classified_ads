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
        $user->status = 'CREATED';
        $user->save();

        return $user;
    }

    public function changeUserStatus(User $user, string $status)
    {
        User::where('id', $user->id)
        ->update(['status' => $status]);
    }
}
