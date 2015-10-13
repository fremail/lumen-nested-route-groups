<?php

namespace Fremail\NestedRouteGroups;

/**
 * Class Application
 */
class Application extends \Laravel\Lumen\Application
{

    protected $namespaceStack = [];
    protected $prefixesStack = [];
    protected $middlewareStack = [];

    /**
     * {@inheritdoc}
     */
    public function group(array $attributes, \Closure $callback)
    {
        // merge middleware
        if (!empty($attributes['middleware'])) {
            // prepare the last middleware
            $lastMiddleware = end($this->middlewareStack);
            $lastMiddleware = is_array($lastMiddleware) ? $lastMiddleware : [];
            // prepare current middleware
            $middleware = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            // merge middleware
            $attributes['middleware'] = array_merge($lastMiddleware, $middleware);
        } else {
            $attributes['middleware'] = end($this->middlewareStack) ? : null;
        }
        // merge prefixes
        if (!empty($attributes['prefix'])) {
            if (count($this->prefixesStack)) {
                $attributes['prefix'] = end($this->prefixesStack) . '/' . trim($attributes['prefix'], '/');
            } else {
                $attributes['prefix'] = trim($attributes['prefix'], '/');
            }
        } else {
            $attributes['prefix'] = end($this->prefixesStack) ? : null;
        }
        // merge namespace
        if (!empty($attributes['namespace'])) {
            if (count($this->namespaceStack)) {
                $attributes['namespace'] = end($this->namespaceStack) . '\\' . trim($attributes['namespace'], '\\');
            } else {
                $attributes['namespace'] = trim($attributes['namespace'], '\\');
            }
        } else {
            $attributes['namespace'] = end($this->namespaceStack) ? : null;
        }

        // merge attributes
        $this->groupAttributes = isset($this->groupAttributes) ? array_merge($this->groupAttributes, $attributes) : $attributes;

        // save current middleware for nested routes
        $this->middlewareStack[] = !empty($attributes['middleware']) ? $attributes['middleware'] :
            !empty($this->groupAttributes['middleware']) ? $this->groupAttributes['middleware'] : [];
        // save a current prefix for nested routes
        $this->prefixesStack[] = !empty($attributes['prefix']) ? trim($attributes['prefix'], '/') :
            !empty($this->groupAttributes['prefix']) ? $this->groupAttributes['prefix'] : '';
        // save a current namespace for nested routes
        $this->namespaceStack[] = !empty($attributes['namespace']) ? trim($attributes['namespace'], '\\') :
            !empty($this->groupAttributes['namespace']) ? $this->groupAttributes['namespace'] : '';

        // var_dump($this->groupAttributes); // uncomment this line to debug routing attributes
        call_user_func($callback, $this);

        // remove the last prefix and last middleware since we got the end of this group branch
        array_pop($this->middlewareStack);
        array_pop($this->prefixesStack);
        array_pop($this->namespaceStack);
    }

}
