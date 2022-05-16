<?php
/**
 * Index.php
 * App starting script.
 * 16/05/2022
 */

// enable autoload
require_once __DIR__.'/vendor/autoload.php';

use app\core\Application;

$app = new Application();

// get the route url and set the display page base on the route
$app->router->get('/', function(){
   return 'durian framework';
});

$app->router->get('/contact', function(){
    return 'contact';
});

$app->run();