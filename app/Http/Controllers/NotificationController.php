<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
	public function index()
	{
		$notifications = Notification::where('to_user_id', auth()->id())
			->with('quote')
			->with('fromUser')
			->orderBy('id', 'desc')->limit(15)->get();
		return response()->json(['data' => $notifications]);
	}

	public function markAsRead(Notification $notification)
	{
		if ($notification->to_user_id !== auth()->id())
		{
			return response()->json(['message' => __('responses.notification_forbidden')], 403);
		}
		$notification = tap($notification)->update(['is_read' => true]);
		return response()->json($notification->load('fromUser'));
	}

	public function markAllAsRead()
	{
		Notification::where(['to_user_id' => auth()->id(), 'is_read' => false])
			->update(['is_read' => true]);
		return response()->json(['message' => __('responses.notification_all_read')]);
	}
}
