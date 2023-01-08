<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationWithoutLoginRequest extends EmailVerificationRequest
{
	public function authorize(): bool
	{
		$user = User::find($this->route('id'));

		return hash_equals(
			(string)$this->route('hash'),
			sha1($user->getEmailForVerification())
		);
	}

	public function fulfill()
	{
		$user = User::find($this->route('id'));

		if ($user->hasVerifiedEmail())
		{
			return 'This email address is already verified';
		}

		$user->markEmailAsVerified();
		event(new Verified($user));
	}
}
