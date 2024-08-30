<?php
declare(strict_types=1);

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Equipment\Application\Services\EquipmentService;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use App\Equipment\infrastructure\Validator\UpdateEquipmentValidator;
use Exception;
use InvalidArgumentException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: '/api/equipment/{id}',
    summary: 'Update an existing equipment by ID',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'type', 'madeBy'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Updated Excavator'),
                new OA\Property(property: 'type', type: 'string', example: 'Heavy Machinery'),
                new OA\Property(property: 'madeBy', type: 'string', example: 'Caterpillar')
            ]
        )
    ),
    tags: ['Equipment'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'ID of the equipment to update',
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
            description: 'Equipment updated successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Equipment', type: 'object')
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
            response: 422,
            description: 'Unprocessable Entity - Invalid input data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Invalid input data')
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
class UpdateEquipmentController
{

    private UpdateEquipmentValidator $validator;
    private IdValidator $idValidator;

    public function __construct(
        readonly private EquipmentService $equipmentService
    ){
        $this->validator = new UpdateEquipmentValidator();
        $this->idValidator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $id = $this->idValidator->validate($args);
            $equipment = $this->validator->validate($request);
            $equipment = $this->equipmentService->updateEquipment($id->getId(), $equipment);
            return JsonResponseBuilder::successRequest('success', [
                'data' => $equipment->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (EquipmentNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}