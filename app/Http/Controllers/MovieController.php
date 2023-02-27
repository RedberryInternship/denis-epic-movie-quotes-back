<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyMovieRequest;
use App\Http\Requests\SearchMovieRequest;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use Illuminate\Support\Facades\Storage;
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

	public function index(SearchMovieRequest $request)
	{
		$searchQuery = $request->validated('search_query');
		$movies = Movie::where('user_id', auth()->user()->id)
			->filter($searchQuery)
			->withCount('quotes')
			->orderBy('updated_at', 'desc')
			->get();

		return response()->json(['data' => $movies]);
	}

	public function store(StoreMovieRequest $request)
	{
		$attributes = $request->validated();
		$attributes = $this->reformatTranslatablesToArrays($attributes, ['title', 'description', 'director']);

		unset($attributes['genres']);
		$attributes['user_id'] = auth()->user()->id;

		$image = request()->file('image');
		$attributes['image'] = $image->store('images');

		$movie = Movie::create($attributes);
		$genres = explode(',', $request['genres']);
		$movie->genres()->attach($genres);

		return response()->json(['message' => __('responses.movie_success')]);
	}

	public function update(Movie $movie, UpdateMovieRequest $request)
	{
		$attributes = $request->validated();
		$attributes = $this->reformatTranslatablesToArrays($attributes, ['title', 'description', 'director']);
		unset($attributes['genres'], $attributes['image']);

		$image = request()->file('image');
		if ($image)
		{
			Storage::delete($movie->getRawOriginal('image'));
			$attributes['image'] = $image->store('images');
		}
		$movie->update($attributes);

		$genres = explode(',', $request['genres']);
		$movie->genres()->sync($genres);

		return response()->json(['message' => __('responses.movie_update_success')]);
	}

	public function destroy(Movie $movie, DestroyMovieRequest $request)
	{
		if ($movie->user_id !== auth()->id())
		{
			return response()->json(['message' => __('responses.movie_delete_forbidden')], 403);
		}
		Storage::delete($movie->getRawOriginal('image'));
		$movie->delete();
		return response()->json(['message' => __('responses.movie_delete_success')]);
	}

	protected function reformatTranslatablesToArrays($attributes, $translatableKeys)
	{
		foreach ($translatableKeys as $key)
		{
			$attributes[$key] = [
				'en' => $attributes[$key . '_en'],
				'ka' => $attributes[$key . '_ka'],
			];
			unset($attributes[$key . '_en'], $attributes[$key . '_ka']);
		}
		return $attributes;
	}
}
