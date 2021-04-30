<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'organization_id',
        'site_type_id',
        'country_id',
        'internal_id',
        'name',
        'address1',
        'address2',
        'address3',
        'zip_code',
        'city',
        'state_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function siteType()
    {
        return $this->belongsTo(SiteType::class);
    }

    public function classifiedAds()
    {
        return $this->hasMany(ClassifiedAd::class);
    }
}
