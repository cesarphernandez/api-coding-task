<?php

namespace App\Equipment\infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

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