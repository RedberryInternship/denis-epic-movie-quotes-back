<?php

namespace App\Http\Requests;

use App\Rules\CurrentYearOrLess;
use App\Rules\Language;
use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'title_en'       => ['required', 'string', new Language('en')],
			'title_ka'       => ['required', 'string', new Language('ka')],
			'description_en' => ['required', 'string', new Language('en')],
			'description_ka' => ['required', 'string', new Language('ka')],
			'director_en'    => ['required', 'string', new Language('en')],
			'director_ka'    => ['required', 'string', new Language('ka')],
			'release_year'   => ['required', 'numeric', 'min:1888', new CurrentYearOrLess],
			'budget'         => ['required', 'numeric'],
			'image'          => ['required', 'image'],
			'genres'         => ['required', 'exists:genres,id'],
		];
	}
}
