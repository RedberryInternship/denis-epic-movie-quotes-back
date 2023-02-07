<?php

namespace App\Http\Requests;

use App\Rules\CurrentYearOrLess;
use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
{
	public function rules()
	{
		return [
			'title_en'       => ['required', 'string'],
			'title_ka'       => ['required', 'string'],
			'description_en' => ['required', 'string'],
			'description_ka' => ['required', 'string'],
			'director_en'    => ['required', 'string'],
			'director_ka'    => ['required', 'string'],
			'release_year'   => ['required', 'numeric', 'min:1888', new CurrentYearOrLess],
			'budget'         => ['required', 'numeric'],
			'image'          => ['required', 'image'],
			'genres'         => ['required', 'exists:genres,id'],
		];
	}
}
