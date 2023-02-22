<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
	public function get()
	{
		return auth()->user()
					 ->load('emails')
					 ->makeVisible(['google_id']);
	}

	public function update(UpdateProfileRequest $request)
	{
		$attributes = $request->validated();

		if (isset($attributes['image']))
		{
			$attributes['profile_picture'] = $this->uploadImage();
			$this->deleteImage(auth()->user()->getRawOriginal('profile_picture'));
		}

		auth()->user()->update($attributes);

		return response()->json(['message' => __('responses.profile_updated')]);
	}

	protected function uploadImage(): string
	{
		$image = request()->file('image');
		return $image->store('images');
	}

	protected function deleteImage($image)
	{
		if ($image)
		{
			Storage::delete($image);
		}
	}
}
