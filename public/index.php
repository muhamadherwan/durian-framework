<?php
/**
 * Index.php
 * App starting script.
 * 16/05/2022
 */

// enable autoload
require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Application;

$app = new Application(dirname(__DIR__));

// get the route url and set the display page base on the route
$app->router->get('/', 'home');

$app->router->get('/contact','contact');

$app->run();