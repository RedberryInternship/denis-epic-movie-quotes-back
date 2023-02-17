<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
	use Queueable;

	public function __construct($token, $customEmail = null)
	{
		parent::__construct($token);
		$this->customEmail = $customEmail;
	}

	protected function resetUrl($notifiable)
	{
		$frontendUrl = config('app.frontend_url') . '/' . app()->getLocale() . '/';

		return url($frontendUrl) . '?' . http_build_query([
			'password_reset' => true,
			'token'          => $this->token,
			'email'          => $notifiable->getEmailForPasswordReset(),
		]);
	}

	public function toMail($notifiable)
	{
		$resetUrl = $this->resetUrl($notifiable);

		return (new MailMessage)
			->subject(__('mail.reset_subject'))
			->view(
				'mail.reset-password',
				['username' => $notifiable->username, 'resetUrl' => $resetUrl]
			);
	}
}
