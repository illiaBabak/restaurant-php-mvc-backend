<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

use Core\Mongo;
use Core\Router;
use App\Controllers\WaiterController;

Mongo::connect();

$router = new Router();

$router->get('/waiters', WaiterController::class . '@getWaiters');
$router->post('/waiters', WaiterController::class . '@createWaiter');
$router->put('/waiters/{id}', WaiterController::class . "@updateWaiter");
$router->delete('/waiters/{id}', WaiterController::class . "@deleteWaiter");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
}

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
