<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Hash;
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

		if (isset($attributes['password']) && !$this->passwordIsCorrect($attributes['current_password']))
		{
			return response()->json(
				['errors' => ['current_password' => ['The password is incorrect']]],
				403
			);
		}

		if (isset($attributes['image']))
		{
			$attributes['profile_picture'] = $this->uploadImage();
			$this->deleteImage(auth()->user()->getRawOriginal('profile_picture'));
		}

		auth()->user()->update($attributes);

		return response()->json(['message' => 'Your details have been updated']);
	}

	protected function passwordIsCorrect($password)
	{
		return Hash::check($password, auth()->user()->password);
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
