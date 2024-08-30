<?php

namespace App\Equipment\Domain\Repository;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\Exceptions\EquipmentNotCreatedException;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;

interface EquipmentRepositoryInterface
{
    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipment(int $id): Equipment;

    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipments(): array;

    /**
     * @throws EquipmentNotCreatedException
     */
    public function createEquipment(Equipment $equipment): Equipment;

    /**
     * @throws EquipmentNotFoundException
     */
    public function updateEquipment(int $id, array $equipment): Equipment;
    public function deleteEquipment(int $id): bool;

}