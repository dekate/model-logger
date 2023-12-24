<?php

namespace Dekate\ModelLogger;

use Illuminate\Support\ServiceProvider;

class ModelLoggerServiceProvider extends ServiceProvider
{
  /**
   * Publishes configuration file.
   *
   * @return  void
   */
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/config/model-logger.php' => config_path('model-logger.php'),
    ], ['model-logger', 'model-logger:config']);
    $this->publishes([
      __DIR__.'/migrations/' => database_path('migrations')
    ], ['migrations', 'model-logger', 'model-logger:migrations']);
  }
  /**
   * Make config publishment optional by merging the config from the package.
   *
   * @return  void
   */
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/config/model-logger.php',
      'model-logger'
    );
  }
}
