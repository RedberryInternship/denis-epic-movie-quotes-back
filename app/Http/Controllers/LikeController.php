<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;

class LikeController extends Controller
{
	public function like(LikeRequest $request)
	{
		$args = $request->validated();
		$userID = auth()->user()->id;

		Like::where([
			'user_id'  => $userID,
			'quote_id' => $args['quote_id'],
		])->delete();

		if ($args['is_unlike_attempt'])
		{
			return response()->json(['message' => 'Likes have been deleted']);
		}

		Like::create([
			'user_id' => $userID,
			'quote_id'=> $args['quote_id'],
		]);

		$targetQuote = Quote::find($args['quote_id']);
		if ($targetQuote->user->id !== $userID)
		{
			$notification = Notification::create(
				[
					'is_comment'   => false,
					'from_user_id' => $userID,
					'to_user_id'   => $targetQuote->user->id,
				]
			);
			NotificationEvent::dispatch($notification);
		}

		return response()->json(['message' => 'Quote liked successfully.']);
	}
}
