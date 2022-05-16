<?php

namespace app\core;

/**
 * Class Application
 * Return the page content based on the requested url.
 * 16/05/2022
 */
class Application
{
    public Router $router;
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    public function run()
    {
        $this->router->resolve();
    }
}