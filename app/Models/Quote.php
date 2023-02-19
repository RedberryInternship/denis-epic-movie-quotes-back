<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Storage;
use Str;

class Quote extends Model
{
	use HasFactory;

	use HasTranslations;

	protected $guarded = [];

	public array $translatable = ['body'];

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

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}

	public function scopeSearchByBodyOrMovieTitle($sqlQuery, $searchQuery = null)
	{
		if (!$searchQuery)
		{
			return;
		}

		$cleanSearchQuery = $this->removeCharIfStartsWith($searchQuery, '#');
		$cleanSearchQuery = $this->removeCharIfStartsWith($cleanSearchQuery, '@');

		if (!Str::startsWith($searchQuery, '@'))
		{
			$sqlQuery->where('body', 'LIKE', "%$cleanSearchQuery%")
					 ->orWhere('body->ka', 'LIKE', "%$cleanSearchQuery%");
		}
		if (!Str::startsWith($searchQuery, '#'))
		{
			$sqlQuery->orWhereHas('movie', function ($query) use ($cleanSearchQuery) {
				$query->where('title', 'LIKE', "%$cleanSearchQuery%")
					  ->orWhere('title->ka', 'LIKE', "%$cleanSearchQuery%");
			});
		}
	}

	public function scopeCurrentUserLikes($sqlQuery)
	{
		$sqlQuery->with(['likes' => fn ($likesQuery) => $likesQuery->where('user_id', auth()->user()->id)]);
	}

	protected function removeCharIfStartsWith($string, $character)
	{
		if (Str::startsWith($string, $character))
		{
			$string = substr($string, 1);
		}
		return $string;
	}
}
