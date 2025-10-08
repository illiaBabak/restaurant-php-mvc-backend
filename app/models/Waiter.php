<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;

class Waiter
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = Mongo::getDB()->selectCollection('waiters');
    }

    public function getAll(): array
    {
        return $this->collection->find()->toArray();
    }

    public function create(array $waiterData): string
    {
        $result = $this->collection->insertOne($waiterData);
        return (string)$result->getInsertedId();
    }

    public function update(string $waiterId, array $waiterData): bool
    {
        $result = $this->collection->updateOne(['id' => $waiterId], ['$set' => $waiterData]);
        return $result->getModifiedCount() > 0;
    }

    public function delete(string $waiterId): bool
    {
        $result = $this->collection->deleteOne(['id' => $waiterId]);
        return $result->getDeletedCount() > 0;
    }
}
