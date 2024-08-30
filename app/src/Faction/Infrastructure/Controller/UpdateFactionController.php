<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use App\Faction\Infrastructure\Validator\UpdateFactionValidator;
use Exception;
use InvalidArgumentException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use OpenApi\Attributes as OA;

#[OA\Put(
    path: '/api/factions/{id}',
    summary: 'Actualizar una facción por ID',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'description'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Updated Faction Name'),
                new OA\Property(property: 'description', type: 'string', example: 'Updated description of the faction')
            ]
        )
    ),
    tags: ['Factions'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'ID de la facción a actualizar',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
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
        new OA\Response(
            response: 200,
            description: 'Facción actualizada con éxito',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Faction')
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: 'Facción no encontrada',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Faction not found')
                ]
            )
        ),
        new OA\Response(
            response: 422,
            description: 'Error en la solicitud',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Invalid input data')
                ]
            )
        ),
        new OA\Response(
            response: 500,
            description: 'Error interno del servidor',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Internal server error')
                ]
            )
        )
    ]
)]
class UpdateFactionController
{
    private UpdateFactionValidator $validator;
    private IdValidator $idValidator;

    public function __construct(
        readonly private FactionService $factionService,
    )
    {
        $this->validator = new UpdateFactionValidator();
        $this->idValidator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $dto = $this->validator->validate($request);
            $id = $this->idValidator->validate($args);
            $updated = $this->factionService->updateFaction($id->getId(), $dto);

            return JsonResponseBuilder::successRequest('success', [
                'data' => $updated->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (FactionNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }


}