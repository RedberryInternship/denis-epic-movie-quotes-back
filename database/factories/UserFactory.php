<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'username'        => fake()->unique()->userName(),
			'password'        => 'password',
			'remember_token'  => Str::random(10),
			'profile_picture' => 'https://picsum.photos/seed/' . fake()->word() . '/100/100',
		];
	}
}
