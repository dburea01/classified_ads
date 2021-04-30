<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

trait HasUuid
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
            if (Auth::check()) {
                $model->created_by = Auth::user()->id;
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::user()->id;
            }
        });
    }
}
