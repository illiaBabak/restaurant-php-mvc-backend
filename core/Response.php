<?php

declare(strict_types=1);

namespace Core;

use Core\Helper;

final class Response
{
    public static function json(mixed $data, int $status = 200, array $headers = []): string
    {
        header('Content-Type: application/json');
        http_response_code($status);

        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }

        return json_encode(['data' => Helper::normalizeMongoId($data), 'error' => null], JSON_UNESCAPED_UNICODE);
    }

    public static function error(string $message, int $status = 500): string
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        $payload = [
            'data' => null,
            'error' => $message
        ];

        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    public static function getBodyFromRequest(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
