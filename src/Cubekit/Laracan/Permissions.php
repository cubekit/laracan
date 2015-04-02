<?php namespace Cubekit\Laracan;

use ReflectionClass;

class Permissions {

    private $permissions = null;

    public function __construct()
    {
        $this->permissions = [];
    }

    public function can($action, $model)
    {
        $subject = $this->getModelSubject($model);

        if ( ! $this->has($subject)) {

            return false;
        }

        return (boolean) $this->find($this->permissions[$subject], $action, $model);
    }

    public function add($action, $subject, $params = null)
    {
        if ( ! $this->has($subject) ) {

            $this->permissions[$subject] = [];
        }

        $this->permissions[$subject][] = new Permission($action, $params);
    }

    private function find($perms, $action, $model)
    {
        return array_first($perms, function($i, $perm) use ($action, $model) {

            return $perm->can($action, $model);
        });
    }

    private function has($subject)
    {
        return ! empty( $this->permissions[$subject] );
    }

    private function getModelSubject($model)
    {
        if (is_string($model)) {

            return $model;
        }

        return (new ReflectionClass($model))->getShortName();
    }
}