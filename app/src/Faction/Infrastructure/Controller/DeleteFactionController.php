<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Controller\BaseController;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Services\FactionService;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class DeleteFactionController extends BaseController
{
    private IdValidator $validator;

    public function __construct(
        private FactionService $factionService,
    )
    {
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $dto = $this->validator->validate($args);
            $success = $this->factionService->deleteFaction($dto->getId());

            if (!$success) {
                return JsonResponseBuilder::notFoundRequest('Faction not found');
            }

            return JsonResponseBuilder::successRequest('success',[
                'message' => 'Faction with id ' . $dto->getId() . ' deleted successfully.'
            ]);
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