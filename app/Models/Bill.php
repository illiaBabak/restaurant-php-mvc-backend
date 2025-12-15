<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\Exception;

class Bill
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = Mongo::getDB()->selectCollection('bills');
    }

    public function create(array $bill): string
    {
        $bill['createdAt'] = new UTCDateTime();

        try {
            $result = $this->collection->insertOne($bill);
            $insertedId = $result->getInsertedId();

            if ($insertedId === null) return '';

            return (string) $insertedId;
        } catch (Exception $e) {
            error_log('MongoDB insert failed: ' . $e->getMessage());
            return '';
        }
    }

    public function update(array $bill): bool
    {
        try {
            $result = $this->collection->updateOne(['_id' => new ObjectId($bill['id'])], ['$set' => $bill]);
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
