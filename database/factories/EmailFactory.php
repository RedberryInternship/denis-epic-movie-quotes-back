<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
	public function definition()
	{
		return [
			'address'     => fake()->unique()->email(),
			'is_primary'  => true,
			'verified_at' => now(),
			'user_id'     => 1,
		];
	}
}
