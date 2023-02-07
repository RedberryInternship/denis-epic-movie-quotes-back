<?php

namespace App\Http\Requests;

class MovieUpdateRequest extends MovieStoreRequest
{
	public function rules()
	{
		$rules = parent::rules();
		$rules['image'] = ['nullable', 'image'];
		return $rules;
	}
}
