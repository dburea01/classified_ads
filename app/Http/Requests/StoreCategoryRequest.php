<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        // dd('organization_id : ' . $this->route('organization')->id . 'category_id : ' . $this->route('category')->id);
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
            'category_group_id' => [
                'required', 'uuid',
                Rule::exists('category_groups', 'id')->where(function ($query) {
                    return $query->where('organization_id', $this->route('organization')->id);
                }),
            ],
            'name' => 'required',
            'position' => 'int|gte:0',
            'state_id' => 'required|in:ACTIVE,INACTIVE'
        ];
    }
}
