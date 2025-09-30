<?php

declare(strict_types=1);

namespace Core;

use MongoDB\Client;

final class Mongo
{
    public static ?Client $client = null;

    public static function connect(): Client
    {
        if (!self::$client) {
            self::$client = new Client(getenv('MONGO_URI'));
        }
        return self::$client;
    }

    public static function getDB(): \MongoDB\Database
    {
        return self::$client->selectDatabase(getenv('MONGO_DB'));
    }
}
