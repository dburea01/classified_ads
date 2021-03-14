<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // dd('organization_id : ' . $this->route('organization')->id . 'category_id : ' . $this->route('category')->id);

        // check if the category_id of the request belongs to the organization of the request
        $category = DB::table('category_groups as cg')
        ->join('categories as c', 'cg.id', 'c.category_group_id')
        ->where('cg.organization_id', $this->route('organization')->id)
        ->where('c.id', $this->route('category')->id)
        ->get();

        return count($category) === 0 ? false : true;
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
            'position' => 'required|int|gt:0',
            'state_id' => 'required|in:ACTIVE,INACTIVE'
        ];
    }
}
