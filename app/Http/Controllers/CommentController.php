<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
	public function store(CommentRequest $request)
	{
		$attributes = $request->validated();
		$attributes['user_id'] = auth()->user()->id;
		Comment::create($attributes);

		return response()->json(['message' => 'Comment created successfully']);
	}
}
