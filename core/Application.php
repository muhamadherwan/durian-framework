<?php

namespace app\core;

/**
 * Class Application
 * Return the page content based on the requested url.
 * 16/05/2022
 */
class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;

    public function __construct($rootPath)
    {
        // set the root path
        self::$ROOT_DIR = $rootPath;

        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}