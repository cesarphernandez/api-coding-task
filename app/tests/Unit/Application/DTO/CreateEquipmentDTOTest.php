<?php

namespace Unit\Application\DTO;

use App\Equipment\Application\DTO\CreateEquipmentDTO;
use PHPUnit\Framework\TestCase;

class CreateEquipmentDTOTest extends TestCase
{
    public function testDTOCreation()
    {
        $dto = new CreateEquipmentDTO(
            'Equipment Name',
            'type',
            'made by'
        );

        $this->assertEquals('Equipment Name', $dto->getName());
        $this->assertEquals('type', $dto->getType());
        $this->assertEquals('made by', $dto->getMadeBy());
    }

}