<?php

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/equipment',
    summary: 'Retrieve a list of all equipment',
    tags: ['Equipment'],
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
        new OA\Response(
            response: 200,
            description: 'List of equipment retrieved successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/Equipment')
                    )
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: 'No equipment found',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'No equipment found')
                ]
            )
        ),
        new OA\Response(
            response: 500,
            description: 'Internal server error',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Internal server error')
                ]
            )
        )
    ]
)]
class ListEquipmentController
{
    public function __construct(
        readonly private EquipmentServiceInterface $equipmentService
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $equipments = $this->equipmentService->getEquipments();

            return JsonResponseBuilder::successRequest('success', [
                'data' => $equipments
            ]);
        } catch (EquipmentNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}