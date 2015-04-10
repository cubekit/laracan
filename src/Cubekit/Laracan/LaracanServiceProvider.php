<?php namespace Cubekit\Laracan;

use Illuminate\Support\ServiceProvider;

use Cubekit\Laracan\Template\CanTag;

class LaracanServiceProvider extends ServiceProvider {

    public function boot()
    {
        require __DIR__ . '/helpers.php';

        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('cubekit/laracan.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton('permissions', function()
        {
            return AbilityInitializer::initialize();
        });

        CanTag::register();
    }

}
