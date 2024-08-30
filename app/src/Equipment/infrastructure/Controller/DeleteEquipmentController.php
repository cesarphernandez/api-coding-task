<?php

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Equipment\Application\Services\EquipmentService;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/api/equipment/{id}',
    summary: 'Delete an equipment by ID',
    tags: ['Equipment'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'ID of the equipment to be deleted',
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
            description: 'Equipment deleted successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Equipment with id 1 deleted successfully.')
                ]
            )
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
class DeleteEquipmentController
{
    private IdValidator $validator;

    public function __construct(
        readonly private EquipmentService $equipmentService
    ) {
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $id = $this->validator->validate($args);
            $success = $this->equipmentService->deleteEquipment($id->getId());

            if (!$success) {
                return JsonResponseBuilder::notFoundRequest('Equipment not found');
            }

            return JsonResponseBuilder::successRequest('success', [
                'message' => 'Equipment with id ' . $id->getId() . ' deleted successfully.'
            ]);
        } catch (EquipmentNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}