<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'categories';

    protected $fillable = [
        'organization_id',
        'category_group_id',
        'name',
        'position',
        'state_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function category_group()
    {
        return $this->belongsTo(CategoryGroup::class);
    }

    public function classifiedAds()
    {
        return $this->hasMany(ClassifiedAd::class);
    }
}
