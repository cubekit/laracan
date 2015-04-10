<?php namespace Cubekit\Laracan;


class AbilityInitializer {

    /**
     * Create permissions instance, load Ability and initialize it to the
     * permissions instance.
     *
     * @return Permissions
     */
    public static function initialize()
    {
        $permissions = new Permissions;

        $user = app('auth')->user();

        static::makeAbility()->initialize($user, function() use ($permissions)
        {
            call_user_func_array( [$permissions, 'add'], func_get_args() );
        });

        return $permissions;
    }

    /**
     * Get the Ability classname from config and make instance of it.
     *
     * @return AbilityContract
     */
    private static function makeAbility()
    {
        return app()->make( config('cubekit.laracan.ability') );
    }

} 