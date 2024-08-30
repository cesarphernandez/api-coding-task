<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotCreatedException;
use App\Faction\Infrastructure\Validator\CreateFactionValidator;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Exception;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/factions',
    summary: 'Crear una nueva facción',
    requestBody: new OA\RequestBody(
        description: 'Faction creation payload',
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'description'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Test 2'),
                new OA\Property(property: 'description', type: 'string', example: 'This is a test faction')
            ]
        )
    ),
    tags: ['Factions'],
    parameters: [
        new OA\Parameter(
            name: 'Authorization',
            description: 'Bearer token for authorization',
            in: 'header',
            required: true,
            schema: new OA\Schema(
                type: 'string',
                example: 'Bearer <your-token-here>'
            )
        )
    ],
    responses: [
        new OA\Response(response: 201, description: 'Facción creada')
    ]
)]

class CreateFactionController
{

    private CreateFactionValidator $validator;

    public function __construct(
        private FactionService $factionService,
    )
    {
        $this->validator = new CreateFactionValidator();
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $dto = $this->validator->validate($request);
            $created = $this->factionService->createFaction($dto);

            return JsonResponseBuilder::createdRequest('success',[
                'data' => $created->toArray()
            ]);
        } catch (FactionNotCreatedException $e) {
            return JsonResponseBuilder::badRequest($e->getMessage());
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::badRequest('Error', [
                'info' => $e->getMessages()
            ]);
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}