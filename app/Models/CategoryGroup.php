<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

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
