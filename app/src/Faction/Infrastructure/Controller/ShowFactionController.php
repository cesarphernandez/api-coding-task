<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Infrastructure\Controller\BaseController;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ShowFactionController extends BaseController
{
    private IdValidator $validator;

    public function __construct(
        private FactionService $factionService,
    ){
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $dto = $this->validator->validate($args);

            $faction = $this->factionService->getFaction($dto->getId());
            return $this->JsonResponse([
                'status' => 'success',
                'data' => $faction->toArray(),
            ], $response, 201);
        } catch (NestedValidationException $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $e->getMessages()
            ]));

            return $response->withStatus(422)
                ->withHeader('Content-Type', 'application/json');
        } catch (FactionNotFoundException $e) {
            return $this->JsonResponse([
                'message' => 'Faction not found',
            ], $response, 404);
        }
    }

}