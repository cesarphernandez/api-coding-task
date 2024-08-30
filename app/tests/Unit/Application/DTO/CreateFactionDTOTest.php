<?php

namespace Unit\Application\DTO;

use PHPUnit\Framework\TestCase;
use App\Faction\Application\DTO\CreateFactionDTO;

class CreateFactionDTOTest extends TestCase
{
    public function testDTOCreation()
    {
        $dto = new CreateFactionDTO('Faction Name', 'Description');

        $this->assertEquals('Faction Name', $dto->getName());
        $this->assertEquals('Description', $dto->getDescription());
    }
}
