<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class OnlyLowercaseAndNumbers implements InvokableRule
{
	public function __invoke($attribute, $value, $fail)
	{
		if (!preg_match('/^[a-z0-9_\-]+$/', $value))
		{
			$fail('The :attribute can only contain lowercase characters and numbers');
		}
	}
}
