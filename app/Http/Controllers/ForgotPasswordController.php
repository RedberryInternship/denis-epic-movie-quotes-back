<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
	public function email(ForgotPasswordRequest $request)
	{
		$request->validated();

		$status = Password::sendResetLink(
			$request->only('email'),
		);

		return $status === Password::RESET_LINK_SENT
			? response()->json(['message' => 'Password reset link sent'])
			: response()->json(['errors' => ['email' => __($status)]], 403);
	}
}
