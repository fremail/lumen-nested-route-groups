<?php

namespace Fremail\NestedRouteGroups;

/**
 * Class Application
 */
class Application extends \Laravel\Lumen\Application
{

    protected $realAttributes;

    /**
     * Register a set of routes with a set of shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure  $callback
     * @return void
     */
    public function group(array $attributes, \Closure $callback)
    {
        $attributes = isset($this->groupAttributes) ? array_merge($this->groupAttributes, $attributes) : $attributes;

        $this->realAttributes = $attributes = isset($this->realAttributes) ? array_merge($this->realAttributes, $attributes) : $attributes;

        parent::group($attributes, $callback);
    }

}