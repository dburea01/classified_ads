<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	use HasFactory;

	protected $table = 'countries';

	protected $fillable = ['id', 'name'];

	public $incrementing = false;

	// tell Eloquent that key is a string, not an integer
	protected $keyType = 'string';
}
