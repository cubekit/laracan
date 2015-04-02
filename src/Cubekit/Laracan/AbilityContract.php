<?php namespace Cubekit\Laracan;

use Closure;

interface AbilityContract {

    public function initialize($user, Closure $can);
}