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
            $uri = getenv('MONGO_URI');

            // Explicit TLS configuration for MongoDB Atlas inside Docker/Alpine
            $options = [
                'tls' => true,
                'tlsCAFile' => '/etc/ssl/certs/ca-certificates.crt',
            ];

            self::$client = new Client($uri, $options);
        }
        return self::$client;
    }

    public static function getDB(): \MongoDB\Database
    {
        return self::$client->selectDatabase(getenv('MONGO_DB'));
    }
}
