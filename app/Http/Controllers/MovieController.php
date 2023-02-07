<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieSearchRequest;
use App\Http\Requests\MovieStoreRequest;
use App\Http\Requests\MovieUpdateRequest;
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

	public function store(MovieStoreRequest $request)
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

		return response()->json(['message' => 'Movie added successfully']);
	}

	public function update(Movie $movie, MovieUpdateRequest $request)
	{
		$attributes = $request->validated();
		$attributes = $this->reformatTranslatablesToArrays($attributes, ['title', 'description', 'director']);

		unset($attributes['genres'], $attributes['image']);
		$attributes['user_id'] = auth()->user()->id;

		$image = request()->file('image');
		if ($image)
		{
			Storage::delete($movie->getRawOriginal('image'));
			$attributes['image'] = $image->store('images');
		}
		$movie->update($attributes);

		$genres = explode(',', $request['genres']);
		$movie->genres()->sync($genres);

		return response()->json(['message' => 'Movie updated successfully']);
	}

	public function destroy(Movie $movie)
	{
		if ($movie->user_id !== auth()->id())
		{
			return response()->json(['message' => 'You can only delete movies added by you'], 403);
		}
		info($movie->getRawOriginal('image'));
		Storage::delete($movie->getRawOriginal('image'));
		$movie->delete();
		return response()->json(['message' => 'Movie deleted successfully']);
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
