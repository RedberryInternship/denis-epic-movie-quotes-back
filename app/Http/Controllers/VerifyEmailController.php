<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationWithoutLoginRequest;

class VerifyEmailController extends Controller
{
	public function index(EmailVerificationWithoutLoginRequest $request)
	{
		$error = $request->fulfill();
		if ($error)
		{
			return response()->json(['message' => $error], 403);
		}

		return response()->json(['message' => __('auth.account_activated')]);
	}
}
