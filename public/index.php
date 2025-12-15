<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Mongo;
use Core\Router;
use App\Controllers\WaiterController;
use App\Controllers\DishController;
use App\Controllers\BillController;

// CORS headers
header("Access-Control-Allow-Origin: https://restaurant-react-frontend-weld.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

Mongo::connect();

$router = new Router();

$router->get('/waiter', WaiterController::class . '@getWaitersByPage');
$router->get('/waiter/all', WaiterController::class . '@getAllWaiters');
$router->post('/waiter', WaiterController::class . '@createWaiter');
$router->put('/waiter', WaiterController::class . '@updateWaiter');
$router->delete('/waiter', WaiterController::class . '@deleteWaiter');

$router->get('/dish', DishController::class . '@getDishesByPage');
$router->post('/dish', DishController::class . '@createDish');
$router->put('/dish', DishController::class . '@updateDish');
$router->delete('/dish', DishController::class . '@deleteDish');

$router->post('/bill', BillController::class . '@createAndExportBill');
$router->put('/bill', BillController::class . '@updateBill');
$router->delete('/bill', BillController::class . '@deleteBill');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
}

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
