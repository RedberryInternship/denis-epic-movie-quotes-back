<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class OnlyLowercaseAndNumbers implements InvokableRule
{
	public function __invoke($attribute, $value, $fail)
	{
		if (!preg_match('/^[a-z0-9_\-]+$/', $value))
		{
			$fail(__('validation.only_lowercase_and_nums', ['attribute' => $attribute]));
		}
	}
}
