<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassifiedAdRequest extends FormRequest
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
            'category_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('organization_id', $this->route('organization')->id);
                })
            ],
            'site_id' => [
                'required',
                'uuid',
                Rule::exists('sites', 'id')->where(function ($query) {
                    return $query->where('organization_id', $this->route('organization')->id);
                })
            ],
            'title' => 'required|min:2',
            'price' => 'required|int|gt:0',
            'currency_id' => 'required|exists:currencies,id',
            // 'ads_status_id' => 'required|in:CREATED,VALIDATED,BLOCKED,ARCHIVED'
        ];
    }
}
