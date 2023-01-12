<?php

namespace App\Http\Requests;

use App\Rules\OnlyLowercaseAndNumbers;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'token'    => 'required',
			'email'    => ['required', 'email'],
			'password' => ['required', 'confirmed', 'max:255', 'min:8', new OnlyLowercaseAndNumbers],
		];
	}
}
