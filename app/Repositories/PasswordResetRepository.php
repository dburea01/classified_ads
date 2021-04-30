<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PasswordReset;
use Carbon\Carbon;

class PasswordResetRepository
{
    public function token_used(string $token): void
    {
        $token = PasswordReset::find($token);
        $token->used_at = now();
        $token->save();
    }

    public function insert(string $email, string $token): PasswordReset
    {
        $expireAt = Carbon::now()->addMinutes(config('params.delay_validity_token_reset_password'));

        $passwordReset = new PasswordReset();
        $passwordReset->email = $email;
        $passwordReset->token = $token;
        $passwordReset->created_at = now();
        $passwordReset->expire_at = $expireAt;
        $passwordReset->save();

        return $passwordReset;
    }

    public function delete(string $email): void
    {
        PasswordReset::where('email', $email)->delete();
    }
}
