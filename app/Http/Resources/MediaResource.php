<?php

namespace App\Http\Resources;

use App\Models\ClassifiedAd;
use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $classifiedAd = ClassifiedAd::find($this->classified_ad_id);
        $organization = Organization::find($classifiedAd->organization_id);

        $url = "{$organization->container_folder}/medias/{$this->storage_name}";

        return [
            'id' => $this->id,
            'storage_name' => $this->storage_name,
            'original_name' => $this->original_name,
            'classified_ad_id' => $this->classified_ad_id,
            'url_media' => Storage::disk('organizations')->url($url)
        ];
    }
}
