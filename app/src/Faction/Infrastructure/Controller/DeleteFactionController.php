<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Services\FactionService;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use OpenApi\Attributes as OA;


#[OA\Delete(
    path: '/api/factions/{id}',
    summary: 'Eliminar una facción por ID',
    tags: ['Factions'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'ID de la facción a eliminar',
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
            description: 'Facción eliminada con éxito',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Faction with id 1 deleted successfully.')
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
            description: 'Error de validación',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'array', items: new OA\Items(type: 'string'))
                ]
            )
        )
    ]
)]
class DeleteFactionController
{
    private IdValidator $validator;

    public function __construct(
        readonly private FactionService $factionService,
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
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessages());
        }
    }

}