<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use App\Models\Email;
use Illuminate\Database\Seeder;
use App\Models\Like;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach (config('genres') as $genre)
		{
			Genre::create(['name' => $genre]);
		}

		User::factory()->create([
			'username' => 'tester',
		]);

		Email::factory()->create([
			'address' => 'tester@mail.com',
			'user_id' => 1,
		]);

		User::factory(10)->make()->each(function ($user) {
			$user->save();

			Email::factory(3)->make()->each(function ($email, $index) use ($user) {
				$email->user()->associate($user);
				if ($index !== 1)
				{
					$email->is_primary = false;
				}
				$email->save();
			});

			Movie::factory(fake()->numberBetween(0, 4))->make()->each(function ($movie) use ($user) {
				$movie->user()->associate($user);
				$movie->save();
				$movie->genres()->attach($this->getUniqueGenreIDs());

				Quote::factory(fake()->numberBetween(0, 7))->make()->each(function ($quote) use ($movie, $user) {
					$quote->user()->associate($user);
					$quote->movie()->associate($movie);
					$quote->save();

					Comment::factory(fake()->numberBetween(0, 6))->make()->each(function ($comment) use ($quote) {
						$comment->user()->associate(User::inRandomOrder()->first());
						$comment->quote()->associate($quote);
						$comment->save();
					});

					Like::factory(fake()->numberBetween(0, 10))->make()->each(function ($like) use ($quote) {
						$like->user()->associate(User::inRandomOrder()->first());
						$like->quote()->associate($quote);
						$like->save();
					});
				});
			});
		});
	}

	protected function getUniqueGenreIDs(): array
	{
		$genreIDs = [];
		$genreCount = rand(1, 4);
		for ($i = 1; $i <= $genreCount; $i++)
		{
			$genreIDs[] = fake()->unique()->numberBetween(1, count(config('genres')));
		}
		fake()->unique($reset = true);

		return $genreIDs;
	}
}
