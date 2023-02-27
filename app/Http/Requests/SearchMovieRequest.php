<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchMovieRequest extends FormRequest
{
	public function rules()
	{
		return [
			'search_query' => ['nullable', 'string'],
		];
	}
}
