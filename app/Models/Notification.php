<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $guarded = [];

	public function toUser()
	{
		return $this->belongsTo(User::class, 'to_user_id');
	}

	public function fromUser()
	{
		return $this->belongsTo(User::class, 'from_user_id');
	}
}
