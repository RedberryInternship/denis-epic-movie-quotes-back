<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteSearchRequest;
use App\Http\Requests\QuoteStoreRequest;
use App\Http\Requests\QuoteUpdateRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Support\Facades\Storage;

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

	public function store(QuoteStoreRequest $request)
	{
		$attributes = $this->getAttributes($request);

		$movie = Movie::find($request['movie_id']);
		if ($movie->user_id !== auth()->id())
		{
			return response()->json(['message' => __('responses.quote_add_forbidden')], 403);
		}

		$image = request()->file('image');
		$attributes['image'] = $image->store('images');
		$attributes['user_id'] = auth()->user()->id;

		Quote::create($attributes);

		return response()->json(['message' => __('responses.quote_add_success')]);
	}

	public function update(Quote $quote, QuoteUpdateRequest $request)
	{
		$attributes = $this->getAttributes($request);

		if ($quote->user_id !== auth()->id())
		{
			return response()->json(['message' => __('responses.quote_update_forbidden')], 403);
		}

		$image = request()->file('image');
		if ($image)
		{
			Storage::delete($quote->getRawOriginal('image'));
			$attributes['image'] = $image->store('images');
		}
		$quote->update($attributes);
		return response()->json(['message' => __('responses.quote_update_success')]);
	}

	public function destroy(Quote $quote)
	{
		if ($quote->user_id !== auth()->id())
		{
			return response()->json(['message' => __('responses.quote_delete_forbidden')], 403);
		}
		Storage::delete($quote->getRawOriginal('image'));
		$quote->delete();
		return response()->json(['message' => __('responses.quote_delete_success')]);
	}

	protected function getAttributes($request)
	{
		$attributes = $request->validated();
		$attributes['body'] = [
			'en' => $attributes['body_en'],
			'ka' => $attributes['body_ka'],
		];
		unset($attributes['body_en'], $attributes['body_ka']);

		return $attributes;
	}
}
