<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    /**
     * Generate an uuid for the key.
     */
    public static function boot(): void
    {
        parent::boot();
        self::creating(function ($model): void {
            $model->id = Uuid::uuid4()->toString();
            if (Auth::check()) {
                $model->created_by = Auth::user()->id;
            }
        });

        self::updating(function ($model): void {
            $model->updated_by = Auth::user()->id;
        });
    }

    public $incrementing = false;

    // tell Eloquent that key is a string, not an integer
    protected $keyType = 'string';

    protected $table = 'categories';

    protected $fillable = [
        'organization_id',
        'category_group_id',
        'name',
        'position',
        'state_id'
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
