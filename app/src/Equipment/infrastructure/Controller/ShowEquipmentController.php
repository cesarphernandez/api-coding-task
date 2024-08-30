<?php

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Validator\IdValidator;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

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