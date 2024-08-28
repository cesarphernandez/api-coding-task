<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Infrastructure\Controller\BaseController;
use App\Faction\Application\Services\FactionService;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ListFactionController extends BaseController
{
    public function __construct(
        private FactionService $factionService,
    ){
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {


            return $this->JsonResponse([
                'status' => 'success',
            ], $response, 201);
        } catch (NestedValidationException $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $e->getMessages()
            ]));

            return $response->withStatus(422)
                ->withHeader('Content-Type', 'application/json');
        }
    }

}