<?php

namespace TaufikT\UAuthRestClient;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Routing\Router;

class ServiceProvider extends BaseServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $this->publishes([
      __DIR__ . '/../config/uauth.php' => config_path('uauth.php'),
    ], ['uauth', 'uauth-config']);

    $this->registerMiddleware();
  }

  /**
   * Register middleware aliases
   */
  protected function registerMiddleware(): void
  {
    $router = $this->app->make(Router::class);

    $router->aliasMiddleware('sso.api', Middleware\ApiMiddleware::class);
  }
}
