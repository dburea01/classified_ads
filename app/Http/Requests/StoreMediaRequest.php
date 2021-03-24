<?php

namespace App\Http\Requests;

use App\Models\Media;
use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    private $classifiedAdId;

    public function __construct(Request $request)
    {
        $this->classifiedAdId = $request->classified_ad_id;
    }

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
            ],
            'media_file' => 'required|mimes:jpg,bmp,png|max:128'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $qtyMedia = Media::where('classified_ad_id', $this->classifiedAdId)->count();

            $organization = Organization::find($this->route('organization')->id);

            if ($qtyMedia >= $organization->media_max) {
                $validator->errors()->add(
                    'media_max',
                    'max media for this organization : ' . $organization->media_max
                );
            }
        });
    }
}
