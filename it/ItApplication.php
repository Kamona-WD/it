<?php
namespace Phpanonymous\It;
/*
$model_prefix = str_replace('/', '\\', 'App\\' . explode('Http', $namespaces)[1]);

$model_prefix = str_replace('\\\\', '\\', $model_prefix);
*/
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Phpanonymous\It\Commands\Generate;

class ItApplication extends ServiceProvider {
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */

	public function boot() {
		include __DIR__ .'/helper/it.php';
		if (!file_exists(base_path('config').'/itconfiguration.php')) {

			$this->publishes([__DIR__ .'/environment/config'    => base_path('config')]);
			$this->publishes([__DIR__ .'/environment/app'       => base_path('app')]);
			$this->publishes([__DIR__ .'/environment/database'  => base_path('database')]);
			$this->publishes([__DIR__ .'/environment/resources' => base_path('resources')]);
			$this->publishes([__DIR__ .'/environment/routes'    => base_path('routes')]);
		}

		Route::middleware('web')
			->prefix('it')
			->group(__DIR__ .'/it_router.php');
		require_once __DIR__ .'/helper/it.php';
		require_once __DIR__ .'/helper/fontawesome.php';
	}
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {

		$this->app->singleton('command.it', function ($app) {
				return new Commands\It;
			});

		$this->app->singleton('command.it.hey', function ($app) {
				return new Commands\ItComeOut;
			});
		$this->app->singleton('command.it.generate', function ($app) {
				return new Commands\Generate;
			});
		$this->app->singleton('command.it.install', function ($app) {
				return new Commands\ItInstaller;
			});
		$this->app->singleton('command.it.uninstall', function ($app) {
				return new Commands\ItUninstall;
			});

		$this->commands([
				Commands\Generate::class ,
				Commands\It::class ,
				Commands\ItComeOut::class ,
				Commands\ItInstaller::class ,
				Commands\ItUninstall::class ,
			]);
		//
	}

	public function provides() {
		return [
			'command.it',
			'command.it.hey',
			'command.it.install',
			'command.it.uninstall',
			'command.it.generate',
		];
	}

}
