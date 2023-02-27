<?php

namespace App\Http\Requests;

use App\Models\Movie;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
	public function authorize()
	{
		$movie = Movie::find($this->movie_id);
		return $movie->user_id === auth()->id();
	}

	public function rules()
	{
		return [
			'body_en'  => ['required', 'string'],
			'body_ka'  => ['required', 'string'],
			'image'    => ['required', 'image'],
			'movie_id' => ['exists:movies,id'],
		];
	}
}
