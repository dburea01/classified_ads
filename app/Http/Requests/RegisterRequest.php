<?php

namespace App\Http\Requests;

use App\Rules\CheckOrganizationDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('organization_id', $request->input('organization_id'));
                }),
                new CheckOrganizationDomain($request->input('organization_id'))],
            'organization_id' => 'required|uuid|exists:organizations,id',
            'password' => 'required|min:6|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire',
            'last_name.required' => 'Le nom et obligatoire',
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'Adresse email incorrecte',
            'email.unique' => 'Adresse email déjà connue pour cette organisation',
            'organization_id.required' => 'Organisation obligatoire',
            'organization_id.uuid' => 'Organisation inconnue',
            'organization_id.exists' => 'Organisation inconnue',
            'password.required' => 'Mot de passe obligatoire',
            'password.confirmed' => 'Mot de passe confirmation doit être identique au mot de passe'
        ];
    }
}
