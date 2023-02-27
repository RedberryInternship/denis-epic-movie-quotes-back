<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Movie extends Model
{
	use HasFactory;

	use HasTranslations;

	protected $guarded = [];

	public array $translatable = ['title', 'description', 'director'];

	public function scopeFilter($sqlQuery, $searchQuery)
	{
		if (!$searchQuery)
		{
			return;
		}

		$sqlQuery->where('title', 'LIKE', "%$searchQuery%")
				 ->orWhere('title->ka', 'LIKE', "%$searchQuery%");
	}

	public function getImageAttribute($value): string
	{
		if (Str::startsWith($value, 'http'))
		{
			return $value;
		}

		return Storage::url($value);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}

	public function genres()
	{
		return $this->belongsToMany(Genre::class);
	}
}
