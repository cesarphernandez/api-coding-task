<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Infrastructure\Controller\BaseController;
use App\Faction\Infrastructure\Validator\CreateFactionValidator;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CreateFactionController extends BaseController
{

    private CreateFactionValidator $validator;

    public function __construct()
    {
        $this->validator = new CreateFactionValidator();
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $dto = $this->validator->validate($request);

            return $this->JsonResponse([
                'status' => 'success',
                'data' => [
                    'faction_name' => $dto->getName(),
                    'description' => $dto->getDescription(),
                ],
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