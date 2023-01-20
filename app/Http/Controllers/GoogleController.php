<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function redirect()
	{
		session(['locale' => app()->getLocale()]);
		return redirect(Socialite::driver('google')
				->redirect()->getTargetUrl() . '&hl=' . app()->getLocale());
	}

	public function callback(Request $request)
	{
		app()->setLocale(session('locale'));
		$frontendUrl = config('app.frontend_url') . '/' . app()->getLocale() . '/';

		try
		{
			$googleUser = Socialite::driver('google')->user();
		}
		catch (Exception $exception)
		{
			return redirect(($frontendUrl) . '?' . http_build_query([
				'oauth_error' => true,
				'message'     => __('auth.google_failed'),
			]));
		}
		$foundEmail = Email::where('address', $googleUser->getEmail())->first();

		if ($foundEmail)
		{
			$user = User::find($foundEmail->user_id);

			if ($user->google_id)
			{
				Auth::login($user);
				$request->session()->regenerate();
				return redirect($frontendUrl . '/home');
			}

			return redirect(($frontendUrl) . '?' . http_build_query([
				'oauth_error' => true,
				'message'     => __('auth.google_failed_already_exists'),
			]));
		}

		$userAttributes = [
			'username'        => $this->generateUniqueUsername($googleUser->getName()),
			'google_id'       => $googleUser->getId(),
			'password'        => '',
			'profile_picture' => $googleUser->getAvatar(),
		];

		$user = User::create($userAttributes);
		Email::create(
			[
				'address'     => $googleUser->getEmail(),
				'is_primary'  => true,
				'user_id'     => $user->id,
				'verified_at' => now(),
			]
		);
		event(new Registered($user));

		Auth::login($user, true);
		$request->session()->regenerate();
		return redirect($frontendUrl . '/home');
	}

	protected function generateUniqueUsername($name)
	{
		if (!$name)
		{
			$name = fake()->word();
		}

		$username = strtolower(preg_replace('/\s+/', '', $name));
		$foundUser = User::where('username', $username)->first();
		$counter = 1;

		$finalUsername = $username;
		while ($foundUser)
		{
			$finalUsername = $username . $counter;
			$foundUser = User::where('username', $finalUsername)->first();
			$counter++;
		}

		return $finalUsername;
	}
}
