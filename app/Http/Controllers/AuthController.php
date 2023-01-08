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

		$found_email = Email::where('address', $attributes['username'])->first();
		if ($found_email)
		{
			$attributes['username'] = User::find($found_email->user_id)->username;
		}

		$attributes = [
			'username' => $attributes['username'],
			'password' => $attributes['password'],
		];

		if (!auth()->attempt($attributes))
		{
			return response()->json([
				'message' => 'The credentials you entered are invalid',
			], 401);
		}
		elseif (!auth()->user()->hasVerifiedEmail())
		{
			return response()->json([
				'message' => 'Please verify your email address before logging in',
			], 401);
		}

		$request->session()->regenerate();

		return response()->json(['message' => 'Successfully logged in']);
	}

	public function register(RegisterRequest $request)
	{
		$attributes = $request->validated();
		$user = User::create($attributes);
		Email::create(['address' => $attributes['email'], 'is_primary' => true, 'user_id' => $user->id]);

		event(new Registered($user));
		return response()->json(['message' => 'Successfully signed up']);
	}
}
