<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use URL;

class VerifyEmailNotification extends VerifyEmail
{
	use Queueable;

	protected function verificationUrl($notifiable)
	{
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
	}

	public function toMail($notifiable)
	{
		$verificationUrl = $this->verificationUrl($notifiable);

		return (new MailMessage)
			->subject('Please verify your email address')
			->line('Movie Quotes')
			->line('Hola ' . $notifiable->username)
			->line('Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:')
			->line('If clicking doesn\'t work, you can try copying and pasting it to your browser:')
			->line('If you have any problems, please contact us: support@moviequotes.ge')
			->line('MovieQuotes Crew')
			->action('Verify account', $verificationUrl);
	}
}
