<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use URL;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		VerifyEmail::createUrlUsing(function ($notifiable) {
			$frontendUrl = config('app.frontend_url');

			$verifyUrl = URL::temporarySignedRoute(
				'verification.verify',
				now()->addMinutes(config('auth.verification.expire', 120)),
				[
					'id'   => $notifiable->getKey(),
					'hash' => sha1($notifiable->getEmailForVerification()),
				]
			);

			return $frontendUrl . '?verify_url=' . urlencode($verifyUrl);
		});

		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			return (new MailMessage)
				->subject('Please verify your email address')
				->line('Movie Quotes')
				->line('Hola ' . $notifiable->username)
				->line('Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:')
				->line('If clicking doesn\'t work, you can try copying and pasting it to your browser:')
				->line('If you have any problems, please contact us: support@moviequotes.ge')
				->line('MovieQuotes Crew')
				->action('Verify account', $url);
		});
	}
}
