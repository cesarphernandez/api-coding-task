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