<?php

namespace Unit\Application\DTO;

use App\Equipment\Application\DTO\PartialEquipmentDTO;
use PHPUnit\Framework\TestCase;

class PartialEquipmentDTOTest extends TestCase
{
    public function testDTOCreation()
    {
        $dto = new PartialEquipmentDTO(
            'Equipment Name',
            null,
            'made by'
        );

        $this->assertEquals('Equipment Name', $dto->getName());
        $this->assertNull($dto->getType());
        $this->assertEquals('made by', $dto->getMadeBy());
    }

}