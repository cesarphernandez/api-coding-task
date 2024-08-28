<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Infrastructure\Controller\BaseController;
use App\common\Infrastructure\Validator\IdValidator;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class DeleteFactionController extends BaseController
{
    private IdValidator $validator;

    public function __construct()
    {
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $dto = $this->validator->validate($args);

            return $this->JsonResponse([
                'status' => 'success',
                'data' => 'Faction deleted successfully with id: ' . $dto->getId(),
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