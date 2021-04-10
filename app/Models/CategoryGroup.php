<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class CategoryGroup extends Model
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

    protected $fillable = [
        'organization_id',
        'name',
        'position',
        'state_id'
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
