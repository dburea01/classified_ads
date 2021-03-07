<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PasswordReset;

class CheckTokenResetPassword implements Rule
{
    private $email;

    public function __construct(string $email = null)
    {
        $this->email = $email;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tokenFound = PasswordReset::where('email', $this->email)
            ->where('token', $value)
            ->where('expire_at', '>=', date('Y-m-d H:i'))
            ->whereNull('used_at')
            ->first();

        return $tokenFound ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Token inconnu ou expiré ou déjà utilisé.';
    }
}
