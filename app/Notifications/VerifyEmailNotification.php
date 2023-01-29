<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use URL;

class VerifyEmailNotification extends VerifyEmail
{
	use Queueable;

	public function __construct($customEmail = null)
	{
		$this->customEmail = $customEmail;
	}

	protected function verificationUrl($notifiable)
	{
		$frontendUrl = config('app.frontend_url') . '/' . app()->getLocale() . '/';

		$verifyUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(config('auth.verification.expire', 120)),
			[
				'id'   => $notifiable->getKey(),
				'hash' => sha1($this->customEmail ?: $notifiable->getEmailForVerification()),
			]
		);

		$baseRedirectUrl = $frontendUrl . ($this->customEmail ? 'profile' : '');
		return $baseRedirectUrl . '?verify_url=' . urlencode($verifyUrl);
	}

	public function toMail($notifiable)
	{
		$verificationUrl = $this->verificationUrl($notifiable);

		return (new MailMessage)
			->subject(__('mail.verify_subject'))
			->view(
				'mail.verify-email',
				[
					'username'           => $notifiable->username,
					'verification_url'   => $verificationUrl,
					'is_secondary_email' => $this->customEmail,
				]
			);
	}
}
