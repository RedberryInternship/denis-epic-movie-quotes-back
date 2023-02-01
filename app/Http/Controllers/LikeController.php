<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Models\Like;

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

		return response()->json(['message' => 'Quote liked successfully.']);
	}
}
