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
			$fail('The :attribute should not exceed the current year');
		}
	}
}
