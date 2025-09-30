<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Response;

use App\Models\Waiters;

class WaitersController
{
    public function getWaiters(): string
    {
        $waiters = new Waiters()->getAll();

        if (!$waiters) {
            return Response::error('Waiters not found');
        }
        return Response::json($waiters);
    }
}
