<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\Exception;
use MongoDB\BSON\UTCDateTime;

const PAGE_SIZE = 10;

class Dish
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = Mongo::getDB()->selectCollection('dishes');
    }

    public function getByPage(int $page): array
    {
        $totalCount = $this->collection->countDocuments();
        $totalPages = (int) ceil($totalCount / PAGE_SIZE);

        $pageData = $this->collection->find(
            [],
            [
                'skip' => ($page - 1) * PAGE_SIZE,
                'limit' => PAGE_SIZE,
                'sort' => ['createdAt' => -1]
            ]
        )->toArray();

        return [
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'pageData' => $pageData,
            'pageSize' => PAGE_SIZE,
            'currentPageNumber' => $page
        ];
    }

    public function getById(string $id): array
    {
        return $this->collection->findOne(['id' => $id]);
    }

    public function create(array $dishData): string
    {
        try {
            $dishData['createdAt'] = new UTCDateTime();
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
            $id = $dishData['id'];
            unset($dishData['id']);
            unset($dishData['createdAt']);

            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $dishData]
            );
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
