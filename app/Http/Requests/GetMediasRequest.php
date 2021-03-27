<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetMediasRequest extends FormRequest
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
            'classified_ad_id' => [
                'required',
                'uuid',
                Rule::exists('classified_ads', 'id')->where(function ($query) {
                    return $query->where('organization_id', $this->route('organization')->id);
                })
            ]
        ];
    }
}
