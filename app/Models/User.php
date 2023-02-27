<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'username',
		'password',
		'google_id',
		'profile_picture',
	];

	protected $hidden = [
		'password',
		'remember_token',
		'google_id',
	];

	public function hasVerifiedEmail(): bool
	{
		return !is_null($this->primaryEmail()->verified_at);
	}

	public function markEmailAsVerified()
	{
		return $this->primaryEmail()->forceFill([
			'verified_at' => $this->freshTimestamp(),
		])->save();
	}

	public function getEmailForVerification()
	{
		return $this->primaryEmailAddress();
	}

	public function routeNotificationForMail($notification)
	{
		return $notification->customEmail ?: $this->primaryEmailAddress();
	}

	protected function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
	}

	public function sendEmailVerificationNotification()
	{
		$this->notify(new VerifyEmailNotification);
	}

	public function sendPasswordResetNotification($token)
	{
		$email = request()->input('email');
		$this->notify(new ResetPasswordNotification($token, $email));
	}

	public function getEmailForPasswordReset()
	{
		return $this->primaryEmailAddress();
	}

	public function primaryEmail()
	{
		return $this->emails()->where('is_primary', true)->first();
	}

	public function primaryEmailAddress()
	{
		return $this->primaryEmail()->address;
	}

	public function emails()
	{
		return $this->hasMany(Email::class);
	}

	public function getProfilePictureAttribute($value): string
	{
		if (Str::startsWith($value, 'http') || !$value)
		{
			return $value ?: '';
		}

		return Storage::url($value);
	}
}
