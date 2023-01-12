<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Extensions\UserWithMultipleEmailsProvider;
use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();
		Auth::provider('user-with-multiple-emails', function ($app, array $config) {
			return new UserWithMultipleEmailsProvider($app['hash'], $config['model']);
		});
	}
}
