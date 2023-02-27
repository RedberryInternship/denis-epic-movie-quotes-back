<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyQuoteRequest extends FormRequest
{
	public function authorize()
	{
		return $this->quote->user_id === auth()->id();
	}

	public function rules()
	{
		return [];
	}
}
