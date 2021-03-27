<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,

            $this->mergeWhen($this->logo, [
                'url_logo' => Storage::disk('organizations')->url("{$this->container_folder}/logos/{$this->logo}"),
            ]),

            // 'url_logo' => Storage::disk('organizations')->url("{$this->container_folder}/logos/{$this->logo}"),
            $this->mergeWhen(Auth::user() && Auth::user()->role_id === 'SUPERADMIN', [
                'contact' => $this->contact,
                'comment' => $this->comment,
                'ads_max' => $this->ads_max,
                'media_max' => $this->media_max,
                'state_id' => $this->state_id,
                'container_folder' => $this->container_folder
            ])
        ];
    }
}
