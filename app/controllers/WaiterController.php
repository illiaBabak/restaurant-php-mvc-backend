<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Response;
use App\Models\Waiter;

class WaiterController
{
    public function getWaiters(): string
    {
        $waiters = new Waiter()->getAll();

        if (!$waiters) {
            return Response::error('Waiters not found', 404);
        }
        return Response::json($waiters);
    }

    public function createWaiter(): string
    {
        $data = Response::getBobyRequest();

        if (!$data) {
            return Response::error('Request body is required', 400);
        }

        $waiterId = new Waiter()->create($data);

        if (!$waiterId) {
            return Response::error('Waiter not created');
        }
        return Response::json($waiterId);
    }

    public function updateWaiter(string $id): string
    {
        $data = Response::getBobyRequest();

        if (!$data || !$id) {
            return Response::error('Request body and id are required', 400);
        }

        $updatedCount = new Waiter()->update($id, $data);

        if (!$updatedCount) {
            return Response::error('Waiter not updated');
        }
        return Response::json("Updated successfully: " . $updatedCount);
    }

    public function deleteWaiter(string $id): string
    {
        if (!$id) {
            return Response::error('Id is required', 400);
        }

        $deletedCount = new Waiter()->delete($id);

        if (!$deletedCount) {
            return Response::error('Waiter not deleted');
        }
        return Response::json("Deleted successfully: " . $deletedCount);
    }
}
