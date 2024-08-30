<?php

namespace App\Equipment\infrastructure\PDO;

use App\Equipment\Domain\Equipment;
use App\Equipment\Domain\Exceptions\EquipmentNotCreatedException;
use App\Equipment\Domain\Exceptions\EquipmentNotFoundException;
use App\Equipment\Domain\Factory\EquipmentFactory;
use App\Equipment\Domain\Repository\EquipmentRepositoryInterface;
use PDO;

class MysqlPDOEquipmentRepository implements EquipmentRepositoryInterface
{
    private EquipmentFactory $factory;
    public function __construct(
        private PDO $connection,
    )
    {
        $this->factory = new EquipmentFactory();
    }

    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipment(int $id): Equipment
    {
        $query = "SELECT * FROM equipments WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() === 0) {
            throw EquipmentNotFoundException::fromId($id);
        }

        $result =  $stmt->fetch();
        return $this->factory->create($result);
    }

    /**
     * @throws EquipmentNotFoundException
     */
    public function getEquipments(): array
    {
        $query = "SELECT * FROM equipments";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        $equipments = [];
        while ($row = $stmt->fetch()) {
            $equipments[] = $this->factory->create($row);
        }
        if (empty($equipments)) {
            throw EquipmentNotFoundException::fromId(0);
        }

        return $equipments;
    }

    /**
     * @throws EquipmentNotCreatedException
     */
    public function createEquipment(Equipment $equipment): Equipment
    {
        $query = "INSERT INTO equipments (name, type, made_by) VALUES (:name, :type, :madeBy)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            'name' => $equipment->getName(),
            'type' => $equipment->getType(),
            'madeBy' => $equipment->getMadeBy()
        ]);

        if ($stmt->rowCount() === 0) {
            throw EquipmentNotCreatedException::fromName($equipment->getName());
        }

        $equipment->setId((int)$this->connection->lastInsertId());

        return $equipment;
    }

    /**
     * @throws EquipmentNotFoundException
     */
    public function updateEquipment(int $id, array $equipment): Equipment
    {
        $currentEquipment = $this->getEquipment($id);
        $query = "UPDATE equipments SET name = :name, type = :type, made_by = :madeBy WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            'name' => $equipment['name'] ?? $currentEquipment->getName(),
            'type' => $equipment['type'] ?? $currentEquipment->getType(),
            'madeBy' => $equipment['madeBy'] ?? $currentEquipment->getMadeBy(),
            'id' => $id
        ]);

        return $this->getEquipment($id);
    }

    public function deleteEquipment(int $id): bool
    {
        $query = "DELETE FROM equipments WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}