<?php namespace Cubekit\Laracan;

use Closure;

class Permission {

    private $action;

    private $params;

    public function __construct($action, $params = null)
    {
        $this->action = $action;
        $this->params = $params;
    }

    public function can($action, $model)
    {
        if ( $this->action != $action ) {

            return false;
        }

        if ( ! $this->params) {

            return true;
        }

        return $this->checkParams($model, $this->params);

    }

    private function checkParams($model, $params)
    {
        if ($params instanceof Closure) {

            return $params($model);
        }

        return empty(
            array_diff_assoc($this->getValues($model, $params), $params)
        );
    }

    private function getValues($model, $params)
    {
        return array_only($model->toArray(), array_keys($params));
    }
}