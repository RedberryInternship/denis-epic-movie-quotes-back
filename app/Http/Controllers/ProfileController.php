<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
	public function get()
	{
		return auth()->user()
					 ->load('emails')
					 ->makeVisible(['google_id']);
	}
}
