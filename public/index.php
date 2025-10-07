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
use App\Controllers\DishController;
use App\Controllers\BillController;

Mongo::connect();

$router = new Router();

$router->get('/waiters', WaiterController::class . '@getWaiters');
$router->post('/waiters', WaiterController::class . '@createWaiter');
$router->put('/waiters/{id}', WaiterController::class . "@updateWaiter");
$router->delete('/waiters/{id}', WaiterController::class . "@deleteWaiter");

$router->get("/dishes", DishController::class . "@getDishes");
$router->post("/dishes", DishController::class . "@createDish");
$router->put("/dishes/{id}", DishController::class . "@updateDish");
$router->delete("/dishes/{id}", DishController::class . "@deleteDish");

$router->post('/bills', BillController::class . '@createAndExportBill');
$router->put('/bills/{id}', BillController::class . '@updateBill');
$router->delete('/bills/{id}', BillController::class . '@deleteBill');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
}

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
