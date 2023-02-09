<?php

namespace App\Http\Requests;

class QuoteUpdateRequest extends QuoteStoreRequest
{
	public function rules()
	{
		$rules = parent::rules();
		$rules['image'] = ['nullable', 'image'];
		return $rules;
	}
}
