<?php

namespace App\Http\Resources;

use App\Models\Organization;
use App\Models\SiteType;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteTypeResource extends JsonResource
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
            'state_id' => $this->state_id,
            'organization' => new OrganizationResource(Organization::find($this->organization_id))
        ];
    }
}
