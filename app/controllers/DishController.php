<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Response;
use App\Models\Dish;

class DishController
{
    public function getDishesByPage(): string
    {
        $page = (int) ($_GET['page'] ?? 1);
        $category = $_GET['category'] ?? null;
        $price = $_GET['price'] ?? null;

        $dishes = new Dish()->getByPage($page, $category, $price);

        if (empty($dishes['pageData'])) {
            return Response::error('Dishes not found for this page', 404);
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
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData) {
            return Response::error("Request body is required", 400);
        }

        $dishId = new Dish()->create($bodyData);

        if (!$dishId) {
            return Response::error("Dish not created");
        }
        return Response::json("Created successfully (dish id: " . $dishId . ")");
    }

    public function updateDish(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData) {
            return Response::error("Request body is required", 400);
        }

        $isUpdated = new Dish()->update($bodyData);

        if (!$isUpdated) {
            return Response::error("Dish not updated");
        }
        return Response::json("Updated successfully");
    }

    public function deleteDish(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData || !isset($bodyData['id'])) {
            return Response::error('Id is required', 400);
        }

        $isDeleted = new Dish()->delete($bodyData['id']);

        if (!$isDeleted) {
            return Response::error('Dish not deleted');
        }
        return Response::json('Deleted successfully');
    }
}
