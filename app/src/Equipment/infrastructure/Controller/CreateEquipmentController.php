<?php
declare(strict_types=1);

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\Equipment\Application\Services\EquipmentService;
use App\Equipment\Domain\Exceptions\EquipmentNotCreatedException;
use App\Equipment\infrastructure\Validator\CreateEquipmentValidator;
use Exception;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/equipment',
    summary: 'Create a new equipment',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'type', 'madeBy'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Excavator'),
                new OA\Property(property: 'type', type: 'string', example: 'Heavy Machinery'),
                new OA\Property(property: 'madeBy', type: 'string', example: 'Caterpillar')
            ]
        )
    ),
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
            response: 201,
            description: 'Equipment created successfully',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'data', ref: '#/components/schemas/Equipment', type: 'object')
                ]
            )
        ),
        new OA\Response(
            response: 400,
            description: 'Bad request or validation error',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Error description')
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
class CreateEquipmentController
{

    private CreateEquipmentValidator $validator;

    public function __construct(
        readonly private EquipmentService $equipmentService
    )
    {
        $this->validator = new CreateEquipmentValidator();
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $equipment = $this->validator->validate($request);
            $equipment = $this->equipmentService->createEquipment($equipment);

            return JsonResponseBuilder::createdRequest('success', [
                'data' => $equipment->toArray(),
            ]);

        } catch (EquipmentNotCreatedException $e) {
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