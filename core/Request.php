<?php

namespace app\core;

/**
 * Class Request
 * Manage the path element based on the requested url.
 * 16/05/2022
 */
class Request
{
    /**
     * Get the path to url before the '?'.
     * @return string
     */
    public function getPath(): string
    {
        // get current url
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        // check if '?' exist in the url and get the '?' position.
        $position = strpos($path, '?');

        // if not exist return the current url.
        if ($position === false) {
            return $path;
        }

        // if exist return the url before the '?'.
        return substr($path,0,$position);

    }

    /**
     * Get the path method (get or post).
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }


}