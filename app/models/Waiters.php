<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;

class Waiters
{
    private \MongoDB\Collection $collection;

    public function __construct()
    {
        $this->collection = Mongo::getDB()->selectCollection('waiters');
    }

    public function getAll(): array
    {
        return $this->collection->find()->toArray();
    }
}
