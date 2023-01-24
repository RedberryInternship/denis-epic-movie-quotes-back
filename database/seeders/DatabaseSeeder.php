<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use App\Models\Email;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		User::factory()->create([
			'username' => 'tester',
		]);

		Email::factory()->create([
			'address' => 'tester@mail.com',
			'user_id' => 1,
		]);

		User::factory(10)->make()->each(function ($user) {
			$user->save();

			Email::factory(3)->make()->each(function ($email) use ($user) {
				$email->user()->associate($user);
				$email->save();
			});

			Movie::factory(2)->make()->each(function ($movie) use ($user) {
				$movie->user()->associate($user);
				$movie->save();

				Quote::factory(3)->make()->each(function ($quote) use ($movie, $user) {
					$quote->user()->associate($user);
					$quote->movie()->associate($movie);
					$quote->save();

					Comment::factory(4)->make()->each(function ($comment) use ($quote) {
						$comment->user()->associate(User::inRandomOrder()->first());
						$comment->quote()->associate($quote);
						$comment->save();
					});
				});
			});
		});
	}
}
