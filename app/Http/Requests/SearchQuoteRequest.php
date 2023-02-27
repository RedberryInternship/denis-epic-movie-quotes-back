<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchQuoteRequest extends FormRequest
{
	public function rules()
	{
		return [
			'search_query' => ['nullable', 'string'],
		];
	}
}
