<?php namespace Cubekit\Laracan\Template;

use Blade;

class CanTag {

    /**
     * Register the @can/@endcan tags pair.
     *
     * @example
     *
     * @can('manage', $project)
     *
     * <a href="{{ route('project.edit', $project) }}">Edit</a>
     *
     * @endcan
     */
    public static function register()
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