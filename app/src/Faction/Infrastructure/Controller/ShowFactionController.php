<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Interfaces\FactionServiceInterface;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Exception;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/factions/{id}',
    summary: 'Obtener detalles de una facción por ID',
    tags: ['Factions'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'ID de la facción',
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
            description: 'Detalles de la facción obtenidos con éxito',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Faction')
                ]
            )
        ),
        new OA\Response(
            response: 400,
            description: 'Error en la solicitud',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'info', type: 'array', items: new OA\Items(type: 'string'))
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


class ShowFactionController
{
    private IdValidator $validator;

    public function __construct(
        readonly private FactionServiceInterface $factionService,
    ){
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $dto = $this->validator->validate($args);

            $faction = $this->factionService->getFaction($dto->getId());
            return JsonResponseBuilder::successRequest('success',[
                'data' => $faction->toArray()
            ]);
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::badRequest('Error', [
                'info' => $e->getMessages()
            ]);
        } catch (FactionNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}