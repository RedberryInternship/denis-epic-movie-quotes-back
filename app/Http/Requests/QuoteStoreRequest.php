<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteStoreRequest extends FormRequest
{
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
