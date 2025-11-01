<?php

declare(strict_types=1);

namespace App\Models;

use Core\Mongo;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\Exception;
use MongoDB\BSON\UTCDateTime;

const PAGE_SIZE = 10;

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

    public function getByPage(int $page, string|null $search): array
    {
        $filter = [];

        if ($search) {
            $filter['$or'] = [
                ['name' => ['$regex' => $search, '$options' => 'i']],
                ['surname' => ['$regex' => $search, '$options' => 'i']],
                ['email' => ['$regex' => $search, '$options' => 'i']],
                ['phone' => ['$regex' => $search, '$options' => 'i']],
            ];
        }

        $totalCount = $this->collection->countDocuments($filter);
        $totalPages = (int) ceil($totalCount / PAGE_SIZE);

        $pageData = $this->collection->find(
            $filter,
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

    public function create(array $waiterData): string
    {
        try {
            $waiterData['createdAt'] = new UTCDateTime();
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
            $id = $waiterData['id'];
            unset($waiterData['id']);
            unset($waiterData['createdAt']);

            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $waiterData]
            );

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
