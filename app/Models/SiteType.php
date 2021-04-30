<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteType extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'organization_id',
        'name',
        'state_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
