<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Str;

class ResetPasswordController extends Controller
{
	public function reset(ResetPasswordRequest $request)
	{
		$request->validated();

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => $password,
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		return $status === Password::PASSWORD_RESET
					? response()->json(['message' => 'Password changed successfully'])
					: response()->json(['errors'  => ['password' => __($status)]], 403);
	}
}
