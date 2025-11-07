<?php

declare(strict_types=1);

namespace Core;

use MongoDB\BSON\ObjectId;

final class Helper
{
    public static function normalizeMongoId(array|object|string $data): array|string
    {
        if (is_string($data)) {
            return $data;
        }

        if (is_object($data)) {
            $data = (array) $data;
        }

        foreach ($data as $key => $value) {
            if ($value instanceof ObjectId) {
                $data[$key] = (string) $value;
                if ($key === '_id') {
                    $data['id'] = (string) $value;
                    unset($data[$key]);
                }
            } else if (is_array($value) || is_object($value)) {
                $data[$key] = self::normalizeMongoId($value);
            }
        }

        return $data;
    }
}
