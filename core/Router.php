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
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
            exit;
        }

        // if callback is string, set the content using renderView()
        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        // if callback is an array
        if (is_array($callback)) {

            // create instant of the controller using the callback first array item
            // and put back the instant inside the callback first array item
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;

        }

        return call_user_func($callback, $this->request);

    }

    // return the layout + content + params data
    public function renderView($view, $params = [])
    {
        // get the layoutContent
        $layoutContent = $this->layoutContent();

        // get the view content based on the requested string
        $viewContent = $this->renderOnlyView($view, $params);

        // return the content and the layout content
        return str_replace('{{content}}',$viewContent, $layoutContent);
    }

//    // return the layout + content
//    public function renderContent($viewContent)
//    {
//        // get the layoutContent
//        $layoutContent = $this->layoutContent();
//
//        // return the content and the layout content
//        return str_replace('{{content}}',$viewContent, $layoutContent);
//    }

    // return the layout
    protected function layoutContent()
    {
//        $layout = Application::$app->controller->layout;
        $layout = Application::$app->controller->layout ?? 'main';

        ob_start();
        include_once Application::$ROOT_DIR."/views/layout/$layout.php";
        return ob_get_clean();
    }

    // return the content
    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value){
            // set the key name same as the value
            $$key = $value;
        }

        ob_start();
        // get the view file based on the requested string
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }












}