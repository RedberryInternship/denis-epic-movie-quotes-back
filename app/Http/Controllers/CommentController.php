<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;

class CommentController extends Controller
{
	public function index($quoteId)
	{
		return response()->json(['data' => Comment::where('quote_id', $quoteId)->with('user')->get()]);
	}

	public function store(CommentRequest $request)
	{
		$attributes = $request->validated();
		$attributes['user_id'] = auth()->user()->id;
		Comment::create($attributes);

		$targetQuote = Quote::find($attributes['quote_id']);
		if ($targetQuote->user->id !== $attributes['user_id'])
		{
			$notification = Notification::create(
				[
					'is_comment'   => true,
					'from_user_id' => $attributes['user_id'],
					'to_user_id'   => $targetQuote->user->id,
					'quote_id'     => $targetQuote->id,
				]
			);
			NotificationEvent::dispatch($notification);
		}

		return response()->json(['message' => __('responses.comment_created')]);
	}
}
