<?php

namespace app\core;

use app\core\exception\NotFoundException;

/**
 * Class Router
 * Return page content based on the requested url.
 * 16/05/2022
 */
class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * Router constructor.
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
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

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * return the layout+content based on the requested path
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();

        // set the page content based on the path.
        $callback = $this->routes[$method][$path] ?? false;

        // if callback not exist return to not found page.
        if ($callback === false) {
//            $this->response->setStatusCode($e->code);
            throw new NotFoundException();
            exit;
        }

        // if callback is string, set the content using renderView()
        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        // if callback is an array
        if (is_array($callback)) {

            // create instant of the controller using the callback first array item
            // and put back the instant inside the callback first array item
            /** @var \app\core\Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }

        }

        return call_user_func($callback, $this->request, $this->response);

    }
    

}