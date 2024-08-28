<?php

declare(strict_types=1);

namespace App\common\Application\Builder;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class JsonResponseBuilder
{
    /**
     * @param string $message
     * @param array $extraData
     * @return ResponseInterface
     */
    public static function badRequest(string $message, array $extraData = []): ResponseInterface
    {
        return self::buildResponse(400, $message, $extraData);
    }

    /**
     * @param string $message
     * @param array $extraData
     * @return ResponseInterface
     */
    public static function unauthorizedRequest(string $message, array $extraData = []): ResponseInterface
    {
        return self::buildResponse(401, $message, $extraData);
    }

    /**
     * @param int $statusCode
     * @param string $message
     * @param array $extraData
     * @return ResponseInterface
     */
    private static function buildResponse(int $statusCode, string $message, array $extraData = []): ResponseInterface
    {
        $response = new Response();
        $payload = array_merge(['status' => 'error', 'message' => $message], $extraData);
        $response->getBody()->write(json_encode($payload));

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json');
    }
}
