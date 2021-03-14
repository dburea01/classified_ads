<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        /*
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'position' => $this->position,
            'name' => $this->name,
            'state_id' => $this->state_id
        ];
        */
    }
}
