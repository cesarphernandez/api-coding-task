<?php

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/equipment/{id}',
    summary: 'Retrieve a specific equipment by ID',
    tags: ['Equipment'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'ID of the equipment to retrieve',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            example: 1
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
            description: 'Equipment retrieved successfully',
            content: new OA\JsonContent(ref: '#/components/schemas/Equipment')
        ),
        new OA\Response(
            response: 404,
            description: 'Equipment not found',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Equipment not found')
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

class ShowEquipmentController
{
    private IdValidator $validator;

    public function __construct(
        readonly private EquipmentServiceInterface $equipmentService
    ) {
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $id = $this->validator->validate($args);
            $equipment = $this->equipmentService->getEquipment($id->getId());

            return JsonResponseBuilder::successRequest('success', [
                'data' => $equipment->toArray()
            ]);
        } catch (EquipmentNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}