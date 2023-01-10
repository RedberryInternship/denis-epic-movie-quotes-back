<?php

namespace Database\Seeders;

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
		User::factory(10)->create();

		User::factory()->create([
			'username' => 'tester',
		]);

		Email::factory(10)->make()->each(function ($email, $index) {
			$email->user()->associate(User::find($index + 1));
			$email->save();
		});

		Email::factory()->create([
			'address' => 'tester@mail.com',
			'user_id' => 11,
		]);
	}
}
