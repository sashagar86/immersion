<?php

namespace App;

class Router
{
    private array $routes;

    /**
     * @param array $routes
     */

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }


    public function get()
    {
        $route = $_SERVER['REQUEST_URI'];

        if (array_key_exists($route, $this->routes)) {
            include $this->routes[$route];
        } else {
            var_dump(404); die;
        }

        return $route;
    }
}