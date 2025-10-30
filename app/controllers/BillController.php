<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Response;
use App\Models\Bill;

class BillController
{

    public function generateCSV(array $bill): void
    {
        header('Content-Type: text/csv; charset=utf-8');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['waiter_id', 'createdAt', 'dish_id', 'quantity'], ',', '"', '\\');

        foreach ($bill['dishes'] as $dish) {
            fputcsv($out, [
                $bill['waiter_id'] ?? '',
                $bill['createdAt'] ?? '',
                $dish['dish_id'] ?? '',
                $dish['quantity'] ?? 0,
            ], ',', '"', '\\');
        }

        fclose($out);
        exit;
    }

    public function createAndExportBill(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData) {
            return Response::error('Request body is required', 400);
        }

        $billId = new Bill()->create($bodyData);

        if (!$billId) {
            return Response::error('Bill not created');
        }

        $this->generateCSV($bodyData);
        exit;
    }

    public function updateBill(): string
    {
        $bodyData = Response::getBodyFromRequest();

        if (!$bodyData) {
            return Response::error('Request body is required', 400);
        }

        $isUpdated = new Bill()->update($bodyData);

        if (!$isUpdated) {
            return Response::error('Bill not updated');
        }
        return Response::json("Updated successfully");
    }

    public function deleteBill(): string
    {
        $id = Response::getBodyFromRequest()['id'];

        if (!$id) {
            return Response::error('Id is required', 400);
        }

        $isDeleted = new Bill()->delete($id);

        if (!$isDeleted) {
            return Response::error('Bill not deleted');
        }
        return Response::json("Deleted successfully");
    }
}
