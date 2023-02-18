<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CurrentYearOrLess implements InvokableRule
{
	public function __invoke($attribute, $value, $fail)
	{
		$currentYear = now()->year;
		if ($value > $currentYear)
		{
			$fail(__('validation.max_current_year', ['attribute' => $attribute]));
		}
	}
}
