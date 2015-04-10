<?php

if ( ! function_exists('can')) {

    function can($action, $model)
    {
        return app('permissions')->can($action, $model);
    }
}