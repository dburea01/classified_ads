<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = ['id', 'local_name', 'english_name', 'currency_id'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function currency()
    {
        return $this->hasOne(Currency::class);
    }
}
