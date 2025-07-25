<?php


require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/application/models/Model.php';
require_once __DIR__ . '/application/models/RequestModel.php';

require_once __DIR__ . '/application/models/User.php';
require_once __DIR__ . '/application/controllers/HomeController.php';
require_once __DIR__ . '/application/controllers/DataController.php';
require_once __DIR__ . '/application/controllers/RequestController.php';
require_once __DIR__ . '/application/controllers/TicketController.php';


require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\DataController;
use Application\Core\Database;
use Application\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\RequestController;
use App\Controllers\TicketController;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$config = require __DIR__ . '/config/config.php';
Database::getInstance($config['db']);


//instance
$router = new Router();
$controller = new HomeController();
$dataController = new DataController();
$requestController = new RequestController();
$ticketController = new TicketController();

$router->add('POST', '/register', [$controller, 'register']);
$router->add('POST', '/login', [$controller, 'login']);

$router->add('GET', '/profile', [$controller, 'profile']);

//data 
$router->add('GET', '/data', [$dataController, 'index']);

//request 
$router->add('GET', '/requests', [$requestController, 'index']);
$router->add('GET', '/request/create', [$requestController, 'create']);
$router->add('POST', '/request/store', [$requestController, 'store']);

$router->add('GET', '/send-tequest-to-url', [$requestController, 'sendRequestToUrl']);

//Ticket
$router->add('GET', '/panel/tickets', [$ticketController, 'allTickets']);



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
