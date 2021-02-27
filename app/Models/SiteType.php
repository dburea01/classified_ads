<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class SiteType extends Model
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

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}
}
