<?php

namespace App\Equipment\Application\Services;

use App\Equipment\Application\DTO\CreateEquipmentDTO;
use App\Equipment\Application\DTO\PartialEquipmentDTO;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\Exceptions\EquipmentNotCreatedException;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use App\Equipment\Domain\Factory\EquipmentFactory;
use App\Equipment\Domain\Repository\EquipmentRepositoryInterface;

class EquipmentService implements EquipmentServiceInterface
{
    private EquipmentFactory $equipmentFactory;
    public function __construct(
        readonly private EquipmentRepositoryInterface $equipmentRepository
    ) {
        $this->equipmentFactory = new EquipmentFactory();
    }

    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipment(int $id): Equipment
    {
        return $this->equipmentRepository->getEquipment($id);
    }

    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipments(): array
    {
        $equipments = $this->equipmentRepository->getEquipments();
        $data = [];
        foreach ($equipments as $equipment) {
            $data[] = $equipment->toArray();
        }
        return $data;
    }

    /**
     * @throws EquipmentNotCreatedException
     */
    public function createEquipment(CreateEquipmentDTO $equipment): Equipment
    {
        $equipment = $this->equipmentFromDTO($equipment);

        return $this->equipmentRepository->createEquipment($equipment);
    }

    /**
     * @throws EquipmentNotFoundException
     */
    public function updateEquipment(int $id, PartialEquipmentDTO $equipment): Equipment
    {
        return $this->equipmentRepository->updateEquipment($id, [
            'name' => $equipment->getName(),
            'type' => $equipment->getType(),
            'made_by' => $equipment->getMadeBy()
        ]);
    }

    public function deleteEquipment(int $id): bool
    {
        return $this->equipmentRepository->deleteEquipment($id);
    }

    private function equipmentFromDTO(CreateEquipmentDTO $equipmentDTO): Equipment
    {
        return $this->equipmentFactory->create([
            'name' => $equipmentDTO->getName(),
            'type' => $equipmentDTO->getType(),
            'made_by' => $equipmentDTO->getMadeBy()
        ]);
    }

}