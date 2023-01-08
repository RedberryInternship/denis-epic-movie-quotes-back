<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'username',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
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
		return $this->hasOne(Email::class, 'user_id');
	}
}
