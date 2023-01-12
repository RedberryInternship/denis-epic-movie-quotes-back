<?php

namespace App\Extensions;

use Closure;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Support\Arrayable;

class UserWithMultipleEmailsProvider extends EloquentUserProvider
{
	public function retrieveByCredentials(array $credentials)
	{
		$credentials = array_filter(
			$credentials,
			fn ($key) => !str_contains($key, 'password'),
			ARRAY_FILTER_USE_KEY
		);

		if (empty($credentials))
		{
			return null;
		}

		$query = $this->newModelQuery();

		foreach ($credentials as $key => $value)
		{
			if ($key === 'email')
			{
				$query->whereHas('emails', function ($query) use ($credentials) {
					$query->where('address', $credentials['email']);
				})->first();
			}
			elseif (is_array($value) || $value instanceof Arrayable)
			{
				$query->whereIn($key, $value);
			}
			elseif ($value instanceof Closure)
			{
				$value($query);
			}
			else
			{
				$query->where($key, $value);
			}
		}

		return $query->first();
	}
}
