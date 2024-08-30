<?php

namespace Unit\Application\DTO;

use App\Faction\Application\DTO\PartialFactionDTO;
use PHPUnit\Framework\TestCase;

class PartialFactionDTOTest extends TestCase
{
    public function testDTOCreation()
    {
        $dto = new PartialFactionDTO('Faction Name', null);

        $this->assertEquals('Faction Name', $dto->getName());
        $this->assertNull($dto->getDescription());
    }

}