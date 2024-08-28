<?php

namespace App\common\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    public function JsonResponse(mixed $data, ResponseInterface $response, ?int $statusCode = null): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));

        if($statusCode){
            $response = $response->withStatus($statusCode);
        }
        return $response;
    }

    public function invalidDataResponse(array $errors, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(json_encode([
            'message' => 'Incorrect data',
            'errors' => $errors
        ], JSON_PRETTY_PRINT));

        return $response->withStatus(422);
    }

}