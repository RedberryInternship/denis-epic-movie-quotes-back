<?php

namespace App\Http\Controllers;

use App\Models\Quote;

class QuoteController extends Controller
{
	public function index()
	{
		$quotes = Quote::with(
			[
				'user',
				'movie',
				'comments.user',
				'likes' => fn ($query) => $query->where('user_id', '=', auth()->user()->id),  // contains logged in user's like if exists, else empty
			]
		)
			->withCount('likes')
			->orderBy('id', 'desc')->cursorPaginate(2);

		return response()->json($quotes);
	}
}
