<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Response;
use App\Models\Waiter;
use Core\MailgunClient;


class WaiterController
{
    public function getAllWaiters(): string
    {
        $waiters = new Waiter()->getAll();

        if (empty($waiters)) {
            return Response::error('No waiters found', 404);
        }
        return Response::json($waiters);
    }

    public function getWaitersByPage(): string
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? null;

        $waiters = new Waiter()->getByPage($page, $search);

        if (empty($waiters['pageData'])) {
            return Response::error('No waiters found for this page', 404);
        }
        return Response::json($waiters);
    }

    public function createWaiter(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData) {
            return Response::error('Request body is required', 400);
        }

        $waiterId = new Waiter()->create($bodyData);

        if (!$waiterId) {
            return Response::error('Waiter not created');
        }

        (new MailgunClient())->sendEmail(
            $bodyData['email'],
            'Hello ' . $bodyData['name'],
            'Congratulations ' . $bodyData['name'] . ', you have been added to the system as a waiter!'
        );

        return Response::json("Created successfully (waiter id: " . $waiterId . ")");
    }

    public function updateWaiter(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData) {
            return Response::error('Request body is required', 400);
        }

        $isUpdated = new Waiter()->update($bodyData);

        if (!$isUpdated) {
            return Response::error('Waiter not updated');
        }
        return Response::json("Updated successfully");
    }

    public function deleteWaiter(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData || !isset($bodyData['id'])) {
            return Response::error('Id is required', 400);
        }

        $isDeleted = new Waiter()->delete($bodyData['id']);

        if (!$isDeleted) {
            return Response::error('Waiter not deleted');
        }
        return Response::json("Deleted successfully");
    }
}
