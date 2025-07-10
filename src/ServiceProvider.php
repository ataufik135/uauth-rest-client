<?php

namespace Taufik\UAuthRestClient;

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
    $configPath = config_path('uauth.php');

    if (file_exists($configPath)) {
      $existingConfig = include $configPath;
      $newConfig = include __DIR__ . '/../config/uauth.php';

      $mergedConfig = array_merge_recursive($existingConfig, $newConfig);

      file_put_contents($configPath, "<?php\n\nreturn " . var_export($mergedConfig, true) . ";\n");
    } else {
      $this->publishes([
        __DIR__ . '/../config/uauth.php' => $configPath,
      ], 'uauth-config');
    }

    // Register middleware aliases
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
