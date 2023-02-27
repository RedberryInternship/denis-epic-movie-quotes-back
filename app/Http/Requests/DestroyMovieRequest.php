<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyMovieRequest extends FormRequest
{
	public function authorize()
	{
		return $this->movie->user_id === auth()->id();
	}

	public function rules()
	{
		return [];
	}
}
