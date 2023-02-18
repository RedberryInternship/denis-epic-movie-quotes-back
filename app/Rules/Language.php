<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\InvokableRule;

class Language implements InvokableRule
{
	public function __construct($language)
	{
		if ($language === 'en')
		{
			$this->language = __('validation.in_english');
			$this->langRegex = 'a-zA-Z';
		}
		elseif ($language === 'ka')
		{
			$this->language = __('validation.in_georgian');
			$this->langRegex = '\p{Georgian}';
		}
		else
		{
			throw new Exception("Invalid language. Only 'en' or 'ka' allowed.");
		}
	}

	public function __invoke($attribute, $value, $fail)
	{
		if (!preg_match('/^[' . $this->langRegex . '\s\p{P}\p{N}\p{S}]+$/u', $value))
		{
			$fail(__('validation.language') . $this->language);
		}
	}
}
