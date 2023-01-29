<?php

namespace App\Http\Requests;

use App\Rules\OnlyLowercaseAndNumbers;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
	public function rules()
	{
		return [
			'username'         => ['max:15', 'min:3', 'unique:users,username', new OnlyLowercaseAndNumbers],
			'current_password' => ['nullable'],
			'password'         => ['confirmed', 'max:255', 'min:8', new OnlyLowercaseAndNumbers],
			'image'            => ['nullable', 'image'],
		];
	}
}
