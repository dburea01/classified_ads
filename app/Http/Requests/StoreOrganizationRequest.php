<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
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
            'name' => 'required|min:2',
            'contact' => 'required',
            'ads_max' => 'required|integer|gt:0',
            'media_max' => 'integer|gt:0',
            'state_id' => 'required|in:VALIDATED,BLOCKED',
            'logo_file' => 'mimes:jpg,bmp,png|max:128'
        ];
    }
}
