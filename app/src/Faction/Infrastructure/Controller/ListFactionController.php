<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\Faction\Application\Interfaces\FactionServiceInterface;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Exception;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/factions',
    summary: 'Listar todas las facciones',
    tags: ['Factions'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Facciones listadas con éxito',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Faction'))
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

class ListFactionController
{
    public function __construct(
        private readonly FactionServiceInterface $factionService,
    ){
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $factions = $this->factionService->getFactions();
            return JsonResponseBuilder::successRequest('success',[
                'data' => $factions
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