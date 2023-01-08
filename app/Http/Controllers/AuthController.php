<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Email;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
	public function register(RegisterRequest $request)
	{
		$attributes = $request->validated();
		$user = User::create($attributes);
		Email::create(['address' => $attributes['email'], 'is_primary' => true, 'user_id' => $user->id]);

		event(new Registered($user));
		return response()->json(['message' => 'Successfully signed up']);
	}
}
