<?php

namespace App\Http\Requests;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationWithoutLoginRequest extends EmailVerificationRequest
{
	public function __construct()
	{
		parent::__construct();
		$this->emailToVerify = null;
	}

	public function authorize(): bool
	{
		$user = User::find($this->route('id'));

		foreach ($user->emails as $email)
		{
			if (hash_equals(
				(string)$this->route('hash'),
				sha1($email->address)
			))
			{
				$this->emailToVerify = $email->address;
				break;
			}
		}

		return (bool)$this->emailToVerify;
	}

	public function fulfill()
	{
		$email = Email::where('address', $this->emailToVerify)->first();

		if ($email->verified_at)
		{
			return 'This email address is already verified';
		}

		$email->forceFill([
			'verified_at' => now(),
		])->save();
	}
}
