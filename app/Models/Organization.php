<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CategoryGroup;

class Organization extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'contact',
        'comment',
        'ads_max',
        'media_max',
        'state_id',
        'logo',
        'container_folder'
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function category_groups()
    {
        return $this->hasMany(CategoryGroup::class);
    }

    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            CategoryGroup::class,
        );
    }

    public function classifiedAds()
    {
        return $this->hasMany(ClassifiedAd::class);
    }
}
