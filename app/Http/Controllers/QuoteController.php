<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteSearchRequest;
use App\Models\Quote;

class QuoteController extends Controller
{
	public function index(QuoteSearchRequest $request)
	{
		$searchQuery = $request->validated('search_query');

		$quotes = Quote::searchByBodyOrMovieTitle($searchQuery)
			->with(
				[
					'user',
					'movie',
					'comments.user',
				]
			)
			->currentUserLikes()
			->withCount('likes')
			->orderBy('id', 'desc')->cursorPaginate(2);

		return response()->json($quotes);
	}
}
