<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
	public function authenticate(LoginRequest $request)
	{
		$attributes = $request->validated();

		$foundEmail = Email::where('address', $attributes['username'])->first();
		if ($foundEmail)
		{
			$attributes['username'] = User::find($foundEmail->user_id)->username;
		}

		$attributes = [
			'username' => $attributes['username'],
			'password' => $attributes['password'],
		];

		if (!auth()->attempt($attributes))
		{
			return response()->json([
				'message' => __('auth.failed'),
			], 401);
		}
		elseif (!auth()->user()->hasVerifiedEmail())
		{
			return response()->json([
				'message' => __('auth.verify'),
			], 401);
		}

		$request->session()->regenerate();

		return response()->json(['message' => __('auth.login_success')]);
	}

	public function register(RegisterRequest $request)
	{
		$attributes = $request->validated();
		$user = User::create($attributes);
		Email::create(['address' => $attributes['email'], 'is_primary' => true, 'user_id' => $user->id]);

		event(new Registered($user));
		return response()->json(['message' => __('auth.register_success')]);
	}

	public function logout()
	{
		auth()->guard('web')->logout();
		return response()->json(['message' => __('auth.logout_success')]);
	}
}
