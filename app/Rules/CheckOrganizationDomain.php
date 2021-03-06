<?php

namespace App\Rules;

use App\Models\Domain;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class CheckOrganizationDomain implements Rule
{
    protected $organizationId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $organizationId)
    {
        $this->organizationId = $organizationId;
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
        $parts = explode('@', $value);

        $domainOfThisEmail = $parts[1];
        // dd($this->organizationId);
        if (!Str::isUuid($this->organizationId)) {
            return false;
        }
        $domain = Domain::where('name', $domainOfThisEmail)->where('organization_id', $this->organizationId)->first();

        return $domain ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This email is not authorized for this organization.';
    }
}
