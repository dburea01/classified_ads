<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class UserHistoEmail extends Model {

    /**
     * Generate an uuid for the key.
     */
    public static function boot(): void {
        parent::boot();
        self::creating(function ($model): void {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public $incrementing = false;
    protected $keyType = 'string';

    // protected $primaryKey = 'id';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User');
    }

}
