<?php
/**
 * Index.php
 * App starting script.
 * 16/05/2022
 */

// enable autoload
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

$app = new Application(dirname(__DIR__));

// get the route url and set the display page base on the route
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);
//$app->router->post('/contact', [SiteController::class, 'handleContact']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->run();