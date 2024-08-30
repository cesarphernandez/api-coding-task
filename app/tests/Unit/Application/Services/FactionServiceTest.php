<?php

namespace Unit\Application\Services;

use App\Faction\Application\DTO\CreateFactionDTO;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Faction;
use App\Faction\Domain\Repository\FactionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FactionServiceTest extends TestCase
{
    private $factionService;
    private $factionRepositoryMock;

    protected function setUp(): void
    {
        $this->factionRepositoryMock = $this->createMock(FactionRepositoryInterface::class);
        $this->factionService = new FactionService($this->factionRepositoryMock);
    }

//    public function testCreateFaction()
//    {
//        $dto = new CreateFactionDTO('New Faction', 'Description');
//
//        $this->factionRepositoryMock
//            ->expects($this->once())
//            ->method('createFaction')
//            ->with($this->isInstanceOf(CreateFactionDTO::class));
//
//        $result = $this->factionService->createFaction($dto);
//        $this->assertInstanceOf(Faction::class, $result);
//    }

}