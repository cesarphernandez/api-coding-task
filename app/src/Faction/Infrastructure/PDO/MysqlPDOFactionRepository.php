<?php

namespace App\Faction\Infrastructure\PDO;

use App\Faction\Domain\Exceptions\FactionNotCreatedException;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use App\Faction\Domain\Faction;
use App\Faction\Domain\Factory\FactionFactory;
use App\Faction\Domain\Repository\FactionRepositoryInterface;
use PDO;

class MysqlPDOFactionRepository implements FactionRepositoryInterface
{
    private FactionFactory $factory;
    public function __construct(
        private PDO $connection
    ) {
        $this->factory = new FactionFactory();
    }

    /**
     * @throws FactionNotFoundException
     */
    public function getFaction(int $id): Faction
    {
        $query = "SELECT * FROM factions WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() === 0) {
            throw FactionNotFoundException::fromId($id);
        }

        $result =  $stmt->fetch();
        return $this->factory->create($result);
    }

    /**
     * @return array<string, Faction>
     * @throws FactionNotFoundException
     */
    public function getFactions(): array
    {
        $query = "SELECT * FROM factions";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $factions = [];
        while ($row = $stmt->fetch()) {
            $factions[] = $this->factory->create($row);
        }
        if (empty($factions)) {
            throw FactionNotFoundException::fromId(0);
        }

        return $factions;
    }

    /**
     * @throws FactionNotCreatedException
     */
    public function createFaction(Faction $faction): Faction
    {
        $query = "INSERT INTO factions (faction_name, description) VALUES (:name, :description)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            'name' => $faction->getName(),
            'description' => $faction->getDescription()
        ]);

        if ($stmt->rowCount() === 0) {
            throw new FactionNotCreatedException('Faction not created');
        }

        $faction->setId((int)$this->connection->lastInsertId());
        return $faction;
    }

    public function updateFaction(int $id, Faction $faction): Faction
    {
        // TODO: Implement updateFaction() method.
    }

    public function deleteFaction(int $id): bool
    {
        // TODO: Implement deleteFaction() method.
    }
}