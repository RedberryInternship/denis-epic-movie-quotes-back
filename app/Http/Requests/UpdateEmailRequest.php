<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailRequest extends FormRequest
{
	public function authorize()
	{
		return $this->email->user_id === auth()->id();
	}

	public function rules()
	{
		return [];
	}
}
