<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
{
	public function authorize()
	{
		return $this->notification->to_user_id !== auth()->id();
	}

	public function rules()
	{
		return [
		];
	}
}
