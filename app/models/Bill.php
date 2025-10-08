<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

class Bill
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = Mongo::getDB()->selectCollection('bills');
    }

    public function create(array $array): string
    {
        $array['created_at'] = new UTCDateTime((int) (strtotime((string) $array['created_at']) * 1000));
        $result = $this->collection->insertOne($array);
        return (string) $result->getInsertedId();
    }

    public function update(string $id, array $array): bool
    {
        $result = $this->collection->updateOne(['id' => $id], ['$set' => $array]);
        return $result->getModifiedCount() > 0;
    }

    public function delete(string $id): bool
    {
        $result = $this->collection->deleteOne(['id' => $id]);
        return $result->getDeletedCount() > 0;
    }
}
