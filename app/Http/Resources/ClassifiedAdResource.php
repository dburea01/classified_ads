<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MediaResource;
use Carbon\Carbon;

class ClassifiedAdResource extends JsonResource
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
            'organization_id' => $this->organization_id,
            'site_id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y H:i'),
            // 'created_at' => $this->created_at,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'currency_id' => $this->currency_id,
            'category' => $this->category,
            'currency' => $this->currency,
            'site' => $this->site,
            'user' => $this->user,
            //'medias' => $this->medias,
            'medias' => MediaResource::collection($this->medias)
        ];
    }
}
