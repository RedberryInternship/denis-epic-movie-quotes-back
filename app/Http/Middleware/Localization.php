<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
	public function handle(Request $request, Closure $next)
	{
		$locale = $request->query('locale') ?: $request->header('Accept-Language');
		if ($locale)
		{
			app()->setLocale($locale);
		}
		return $next($request);
	}
}
