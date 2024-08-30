<?php

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Equipment\Application\Services\EquipmentService;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

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