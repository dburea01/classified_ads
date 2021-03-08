<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckEmailVerificationCode implements Rule
{
    private $email;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::where('email', $this->email)
        ->where('email_verification_code', $value)
        ->where('state_id', 'CREATED')
        ->first();

        return $user ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Impossible to validate this user.';
    }
}
