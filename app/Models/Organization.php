<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CategoryGroup;

class Organization extends Model
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

	public function category_groups()
	{
		return $this->hasMany(CategoryGroup::class);
	}

	public function categories()
	{
		return $this->hasManyThrough(Category::class, CategoryGroup::class);
	}
}
