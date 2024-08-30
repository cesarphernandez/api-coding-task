<?php

namespace App\Equipment\Domain\Factory;

use App\Equipment\Domain\Equipment;

class EquipmentFactory
{
    public function create(array $equipment): Equipment
    {
        return new Equipment(
            $equipment['name'],
            $equipment['type'],
            $equipment['made_by'],
            $equipment['id'] ?? 0
        );
    }

}