<?php

namespace App\Http\Requests;

use App\Rules\CheckTokenResetPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ResetPasswordRequest extends FormRequest
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
            'email' => 'required|email',
            'token' => ['required', new CheckTokenResetPassword($request->email)],
            'password' => 'required|min:6|confirmed'
        ];
    }
}
