<?php

namespace App\Equipment\Application\Interfaces;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;

interface EquipmentServiceInterface
{
    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipment(int $id): Equipment;

    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipments(): array;

}