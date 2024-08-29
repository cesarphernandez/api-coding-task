<?php

declare(strict_types=1);

namespace App\common\Application\Builder;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class JsonResponseBuilder
{
    public static function internalServerErrorRequest(string $message): ResponseInterface
    {
        return self::buildResponse(500, $message);
    }
    public static function badRequest(string $message, array $extraData = []): ResponseInterface
    {
        return self::buildResponse(400, $message, $extraData);
    }

    public static function notFoundRequest(string $message): ResponseInterface
    {
        return self::buildResponse(404, $message);
    }

    public static function unprocessableEntityRequest(string $message): ResponseInterface
    {
        return self::buildResponse(422, $message);
    }

    public static function unauthorizedRequest(string $message, array $extraData = []): ResponseInterface
    {
        return self::buildResponse(401, $message, $extraData);
    }

    public static function unavailableRequest(string $message): ResponseInterface
    {
        return self::buildResponse(503, $message);
    }

    public static function successRequest(string $message, array $extraData = []): ResponseInterface
    {
        return self::buildResponse(200, $message, $extraData);
    }

    private static function buildResponse(int $statusCode, string $message, array $extraData = []): ResponseInterface
    {
        $response = new Response();
        $payload = array_merge(['message' => $message], $extraData);
        $response->getBody()->write(json_encode($payload));

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json');
    }

    public static function baseResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['message' => $message]);
    }
}
