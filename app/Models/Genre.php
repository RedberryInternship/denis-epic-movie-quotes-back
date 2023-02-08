<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
	use HasTranslations;

	public array $translatable = ['name'];

	public function movies()
	{
		return $this->belongsToMany(Movie::class);
	}
}
