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