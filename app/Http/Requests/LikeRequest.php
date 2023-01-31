<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'is_unlike_attempt' => ['required', 'boolean'],
			'quote_id'          => ['required', 'integer'],
		];
	}
}
