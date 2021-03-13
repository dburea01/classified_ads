<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CheckOrganizationDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->where('organization_id', $this->route('organization')->id);
                })->ignore($this->user)
            ],
            'coherence_organization_user' => new CheckOrganizationDomain($this->route('organization')->id, $this->route('user')->id),
            'role_id' => 'required|in:EMPLOYEE,ADMIN',
            'state_id' => 'required|exists:states,id'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $coherenceOrganizationUser = User::where('organization_id', $this->route('organization')->id)
                                    ->where('id', $this->route('user')->id)->first();

            if (!$coherenceOrganizationUser) {
                $validator->errors()->add('user_id', 'This user does not belong to this organization.');
            }
        });
    }

    public function messages()
    {
        return [
            'email.unique' => 'Pas de cohérence entre organization et user'
        ];
    }
}
