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
            return $this->makePermissions();
        });

        CanTag::register();
    }

    private function makePermissions()
    {
        $permissions = new Permissions;

        $user = app('auth')->user();

        $this->makeAbility()->initialize($user, function() use ($permissions)
        {
            call_user_func_array( [$permissions, 'add'], func_get_args() );
        });

        return $permissions;
    }

    private function makeAbility()
    {
        return $this->app->make( config('cubekit.laracan.ability') );
    }

}
