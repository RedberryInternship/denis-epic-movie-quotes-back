<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasFactory;

	use HasTranslations;

	protected $guarded = [];

	public array $translatable = ['body'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function movie()
	{
		return $this->belongsTo(Movie::class, 'movie_id');
	}

	public function comments()
	{
		return $this->hasMany(Comment::class, 'quote_id');
	}

	public function likes()
	{
		return $this->hasMany(Like::class, 'quote_id');
	}
}
