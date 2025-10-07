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

    public function update(string $id, array $array): int
    {
        $result = $this->collection->updateOne(['_id' => $id], ['$set' => $array]);
        return $result->getUpsertedCount();
    }

    public function delete(string $id): int
    {
        $result = $this->collection->deleteOne(['_id' => $id]);
        return $result->getDeletedCount();
    }
}
