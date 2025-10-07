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
        fputcsv($out, ['waiter_id', 'id', 'created_at', 'dish_id', 'count'], ',', '"', '\\');

        foreach ($bill['dishes'] as $dish) {
            fputcsv($out, [
                $bill['waiter_id'] ?? '',
                $bill['id'] ?? '',
                $bill['created_at'],
                $dish['dish_id'] ?? '',
                $dish['count'] ?? 0,
            ], ',', '"', '\\');
        }

        fclose($out);
        exit;
    }

    public function createAndExportBill(): string
    {
        $data = Response::getBobyRequest();

        if (!$data) {
            return Response::error('Request body is required', 400);
        }

        $billId = new Bill()->create($data);

        if (!$billId) {
            return Response::error('Bill not created');
        }

        $this->generateCSV($data);

        return Response::json("a");
    }

    public function updateBill(string $id): string
    {
        $data = Response::getBobyRequest();

        if (!$data || !$id) {
            return Response::error('Request body and id are required', 400);
        }

        $updatedCount = new Bill()->update($id, $data);

        if (!$updatedCount) {
            return Response::error('Bill not updated');
        }
        return Response::json("Updated successfully: " . $updatedCount);
    }

    public function deleteBill(string $id): string
    {
        if (!$id) {
            return Response::error('Id is required', 400);
        }

        $deletedCount = new Bill()->delete($id);

        if (!$deletedCount) {
            return Response::error('Bill not deleted');
        }
        return Response::json("Deleted successfully: " . $deletedCount);
    }
}
