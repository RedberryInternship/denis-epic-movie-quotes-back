<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'title'       => [
				'en' => fake()->realTextBetween(5, 30),
				'ka' => 'ფილმის სახელი',
			],
			'description' => [
				'en' => fake()->realTextBetween(50, 300),
				'ka' => 'ფილმის დეტალური აღწერა',
			],
			'director'    => [
				'en' => fake()->name(),
				'ka' => 'რეჟისორ გვარაძე',
			],
			'image'       => 'https://picsum.photos/seed/' . fake()->word() . '/720/400',
			'user_id'     => 1,
		];
	}
}
