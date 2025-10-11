<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\Exception;

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
        try {
            $result = $this->collection->insertOne($waiterData);
            $insertedId = $result->getInsertedId();

            if ($insertedId === null) return '';

            return (string) $insertedId;
        } catch (Exception $e) {
            error_log('MongoDB insert failed: ' . $e->getMessage());
            return '';
        }
    }

    public function update(array $waiterData): bool
    {
        try {
            $result = $this->collection->updateOne(['_id' => new ObjectId($waiterData['id'])], ['$set' => $waiterData]);
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            error_log('MongoDB update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(string $waiterId): bool
    {
        try {
            $result = $this->collection->deleteOne(['_id' => new ObjectId($waiterId)]);
            return $result->getDeletedCount() > 0;
        } catch (Exception $e) {
            error_log('MongoDB delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
