<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;

class Dish
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = Mongo::getDB()->selectCollection('dishes');
    }

    public function getAll(): array
    {
        return $this->collection->find()->toArray();
    }

    public function getById(string $id): array
    {
        return $this->collection->findOne(['_id' => $id]);
    }

    public function create(array $dishData): string
    {
        $result = $this->collection->insertOne($dishData);
        return (string) $result->getInsertedId();
    }

    public function update(string $id, array $dishData): int
    {
        $filter = ['_id' => $id];
        $result = $this->collection->updateOne($filter, ['$set' => $dishData]);
        return $result->getUpsertedCount();
    }

    public function delete(string $id): int
    {
        $result = $this->collection->deleteOne(['_id' => $id]);
        return $result->getDeletedCount();
    }
}
