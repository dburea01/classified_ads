<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiteRequest extends FormRequest
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
            'site_type_id' => [
                'required',
                'uuid',
                Rule::exists('site_types', 'id')->where(function ($query) {
                    return $query->where('organization_id', $this->route('organization')->id);
                }),
            ],
            'internal_id' => Rule::unique('sites', 'internal_id')->where(function ($query) {
                return $query->where('organization_id', $this->route('organization')->id);
            })->ignore($this->site),
            'country_id' => 'required|exists:countries,id',
            'name' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'state_id' => 'required|in:ACTIVE,INACTIVE'
        ];
    }

    public function messages()
    {
        return [
            'site_type_id.exists' => 'Organization and site_type are incoherent.'
        ];
    }
}
