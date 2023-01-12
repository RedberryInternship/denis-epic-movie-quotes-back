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
			->view(
				'mail.verify-email',
				['username' => $notifiable->username, 'verificationUrl' => $verificationUrl]
			);
	}
}
