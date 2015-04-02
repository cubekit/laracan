<?php namespace Cubekit\Laracan;

use Blade;
use Illuminate\Support\ServiceProvider;

class LaracanServiceProvider extends ServiceProvider {

    private $permissions = null;

    public function boot()
    {
        require __DIR__ . '/helpers.php';
    }

    public function register()
    {
        $this->app->bind('permissions', function() {

            return $this->getPermissions();
        });
//
//        $this->extendBlade();
    }

    private function getPermissions()
    {
        if ( ! $this->permissions) {

            $this->permissions = $this->makePermissions();
        }

        return $this->permissions;
    }

    private function makePermissions()
    {
        $permissions = new Permissions;

        $user = app('auth')->user();

        $this->makeAbility()->initialize($user, function() use ($permissions) {

            call_user_func_array( [$permissions, 'add'], func_get_args() );
        });

        return $permissions;
    }

    private function makeAbility()
    {
        return $this->app->make( app('config')->get('laracan.ability') );
    }

    private function extendBlade()
    {
        Blade::extend(function($view, $compiler)
        {
            $canPattern = $compiler->createMatcher('can');
            $endCanPattern = $compiler->createPlainMatcher('endcan');

            $view = preg_replace($canPattern, '$1<?php if(can$2): ?>', $view );

            return preg_replace($endCanPattern, '$1<?php endif; ?>', $view);
        });
    }

}