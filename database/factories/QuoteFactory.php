<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'body'        => [
				'en' => fake()->realTextBetween(30, 120),
				'ka' => 'ფილმის ციტატა',
			],
			'image'       => 'https://picsum.photos/seed/' . fake()->word() . '/720/400',
			'movie_id'    => 1,
			'user_id'     => 1,
		];
	}
}
