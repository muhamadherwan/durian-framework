<?php

namespace app\core;

/**
 * Class Router
 * Return page content based on the requested url.
 * 16/05/2022
 */
class Router
{
    public Request $request;
    protected array $routes = [];

    /**
     * Router constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Set the route callback.
     *
     * @param $path
     * @param $callback
     * @return void
     */
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * @return void
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        // set the page content based on the path.
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            echo "Not Found";
            exit;
        }
        // else
        echo call_user_func($callback);

    }
}