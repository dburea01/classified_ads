<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassifiedAd extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'site_id',
        'title',
        'description',
        'price',
        'currency_id',
        'ads_status_id'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function medias()
    {
        return $this->hasMany(Media::class);
    }
}
