<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailRequest;
use App\Models\Email;
use App\Notifications\VerifyEmailNotification;

class EmailController extends Controller
{
	public function store(StoreEmailRequest $request)
	{
		$email = $request->validated()['email'];
		Email::create(['address' => $email, 'is_primary' => false, 'user_id' => auth()->user()->id]);
		auth()->user()->notify(new VerifyEmailNotification($email));
		return response()->json(['message' => __('responses.email_created')]);
	}

	public function destroy(Email $email)
	{
		if (!$email->user() === auth()->user())
		{
			return response()->json(['message' => __('responses.email_delete_forbidden')], 403);
		}

		if ($email->is_primary)
		{
			return response()->json(['message' => __('responses.email_delete_primary')], 400);
		}

		$email->delete();

		return response()->json(['message' => __('responses.email_delete_success')]);
	}

	public function makePrimary(Email $email)
	{
		if (!$email->user() === auth()->user())
		{
			return response()->json(['message' => __('responses.email_modify_forbidden')], 403);
		}

		auth()->user()->emails()
			->where('is_primary', true)->first()
			->update(['is_primary' => false]);

		$email->is_primary = true;
		$email->save();

		return response()->json(['message' => __('responses.email_primary_success')]);
	}
}
