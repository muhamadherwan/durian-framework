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

    /**
     *
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        // set the page content based on the path.
        $callback = $this->routes[$method][$path] ?? false;

        // if callback not exist return to not found page.
        if ($callback === false) {
            $this->response->setStatusCode(404);
            return "Not Found";
            exit;
        }

        // if callback is string, set the content using renderView()
        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        // else
        return call_user_func($callback);
    }

    public function renderView($view)
    {
        // get the layoutContent
        $layoutContent = $this->layoutContent();

        // get the view content based on the requested string
        $viewContent = $this->renderOnlyView($view);

        // return the content and the layout content
        return str_replace('{{content}}',$viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layout/main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view)
    {
        ob_start();
        // get the view file based on the requested string
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }












}