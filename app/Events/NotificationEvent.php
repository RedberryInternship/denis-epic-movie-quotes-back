<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public Notification $notification;

	public function __construct(Notification $notification)
	{
		$this->notification = $notification->load('fromUser');
	}

	public function broadcastOn()
	{
		return new PrivateChannel('user_' . $this->notification->to_user_id);
	}
}
