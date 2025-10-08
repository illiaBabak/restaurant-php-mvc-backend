<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Response;
use App\Models\Dish;

class DishController
{
    public function getDishes(): string
    {
        $dishes = new Dish()->getAll();

        if (!$dishes) {
            return Response::error('Dishes not found', 404);
        }
        return Response::json($dishes);
    }

    public function getDishById(string $id): string
    {
        $dish = new Dish()->getById($id);

        if (!$dish) {
            return Response::error('Dish not found', 404);
        }
        return Response::json($dish);
    }

    public function createDish(): string
    {
        $body = Response::getBobyRequest();

        if (!$body) {
            return Response::error("Request body is required", 400);
        }

        $dishId = new Dish()->create($body);

        if (!$dishId) {
            return Response::error("Dish not created");
        }
        return Response::json($dishId);
    }

    public function updateDish(string $id): string
    {
        $body = Response::getBobyRequest();

        if (!$body || !$id) {
            return Response::error("Request body and id are required", 400);
        }

        unset($body['_id']);

        $updatedCount = new Dish()->update($id, $body);

        if (!$updatedCount) {
            return Response::error("Dish not updated");
        }
        return Response::json("Updated successfully: " . $updatedCount);
    }

    public function deleteDish(string $id): string
    {
        if (!$id) {
            return Response::error('Id is required', 400);
        }

        $deletedCount = new Dish()->delete($id);

        if (!$deletedCount) {
            return Response::error('Dish not deleted');
        }
        return Response::json('Deleted successfully' . $deletedCount);
    }
}
