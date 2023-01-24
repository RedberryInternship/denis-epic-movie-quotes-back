<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
	public function definition(): array
	{
		return [
			'quote_id' => 1,
			'user_id'  => 1,
		];
	}
}
