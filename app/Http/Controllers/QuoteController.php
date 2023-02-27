<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyQuoteRequest;
use App\Http\Requests\SearchQuoteRequest;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function index(SearchQuoteRequest $request)
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

	public function store(StoreQuoteRequest $request)
	{
		$attributes = $this->getAttributes($request);
		$image = request()->file('image');
		$attributes['image'] = $image->store('images');
		$attributes['user_id'] = auth()->user()->id;

		Quote::create($attributes);

		return response()->json(['message' => __('responses.quote_add_success')]);
	}

	public function update(Quote $quote, UpdateQuoteRequest $request)
	{
		$attributes = $this->getAttributes($request);
		$image = request()->file('image');
		if ($image)
		{
			Storage::delete($quote->getRawOriginal('image'));
			$attributes['image'] = $image->store('images');
		}
		$quote->update($attributes);
		return response()->json(['message' => __('responses.quote_update_success')]);
	}

	public function destroy(Quote $quote, DestroyQuoteRequest $request)
	{
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
