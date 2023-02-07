<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieSearchRequest;
use App\Models\Movie;
use App\Models\Quote;

class MovieController extends Controller
{
	public function get($id)
	{
		$movie = Movie::find($id)->load('genres');
		$quotes = Quote::where('movie_id', $id)
			->currentUserLikes()
			->withCount('likes')
			->withCount('comments')
			->orderBy('updated_at', 'desc')->get();

		return response()->json(
			[
				'data' => [
					'movie'  => $movie,
					'quotes' => $quotes,
				],
			]
		);
	}

	public function index(MovieSearchRequest $request)
	{
		$searchQuery = $request->validated('search_query');
		$movies = Movie::where('user_id', auth()->user()->id)
			->filter($searchQuery)
			->withCount('quotes')
			->orderBy('updated_at', 'desc')
			->get();

		return response()->json(['data' => $movies]);
	}
}
