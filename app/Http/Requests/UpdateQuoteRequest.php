<?php

namespace App\Http\Requests;

class UpdateQuoteRequest extends StoreQuoteRequest
{
	public function authorize()
	{
		return $this->movie->user_id === auth()->id();
	}

	public function rules()
	{
		$rules = parent::rules();
		$rules['image'] = ['nullable', 'image'];
		return $rules;
	}
}
