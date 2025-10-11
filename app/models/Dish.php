<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\Exception;

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
        return $this->collection->findOne(['id' => $id]);
    }

    public function create(array $dishData): string
    {
        try {
            $result = $this->collection->insertOne($dishData);
            $insertedId = $result->getInsertedId();

            if ($insertedId === null) return '';

            return (string) $insertedId;
        } catch (Exception $e) {
            error_log('MongoDB insert failed: ' . $e->getMessage());
            return '';
        }
    }

    public function update(array $dishData): bool
    {
        try {
            $result = $this->collection->updateOne(['_id' => new ObjectId($dishData['id'])], ['$set' => $dishData]);
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            error_log('MongoDB update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(string $id): bool
    {
        try {
            $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
            return $result->getDeletedCount() > 0;
        } catch (Exception $e) {
            error_log('MongoDB delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
