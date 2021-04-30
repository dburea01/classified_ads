<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid;

    public $incrementing = false;

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organization::class);
    }

    public function classifiedAds()
    {
        return $this->hasMany(ClassifiedAd::class);
    }
}
