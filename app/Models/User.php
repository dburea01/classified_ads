<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'email',
        'first_name',
        'last_name',
        'organization_id',
        'role_id',
        'user_state_id',
    ];

    protected $hidden = [
        'password',
        'email_verification_code'
    ];

    public function organisation()
    {
        return $this->belongsTo(Organization::class);
    }

    public function classified_ads()
    {
        return $this->hasMany(ClassifiedAds::class);
    }
}
