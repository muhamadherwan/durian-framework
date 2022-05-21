<?php
/**
 * migration.php
 * App table migration script.
 * 21/05/2022
 */

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

// enable autoload
require_once __DIR__ . '/vendor/autoload.php';

// load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// set the config data from .env file
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(__DIR__, $config);

$app->db->applyMigrations();