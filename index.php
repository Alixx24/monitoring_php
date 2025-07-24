<?php


require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/application/models/Model.php';
require_once __DIR__ . '/application/models/User.php';
require_once __DIR__ . '/application/controllers/HomeController.php';
require_once __DIR__ . '/application/controllers/DataController.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\DataController;
use Application\Core\Database;
use Application\Core\Router;
use App\Controllers\HomeController;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$config = require __DIR__ . '/config/config.php';
Database::getInstance($config['db']);

$router = new Router();
$controller = new HomeController();
$dataController = new DataController();

$router->add('POST', '/register', [$controller, 'register']);
$router->add('POST', '/login', [$controller, 'login']);

$router->add('GET', '/profile', [$controller, 'profile']);

//data 

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];


$basePath = '/request';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}


if ($uri === '') {
    $uri = '/';
}


$router->dispatch($method, $uri);
