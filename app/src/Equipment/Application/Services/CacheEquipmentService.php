<?php

namespace App\Equipment\Application\Services;

use App\Equipment\Domain\Equipment;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use Predis\Client;

final class CacheEquipmentService implements EquipmentServiceInterface
{
    public function __construct(
        readonly private EquipmentService $equipmentService,
        readonly private Client $redisClient,
        readonly private int $ttl
    ) {
    }

    public function getEquipment(int $id): Equipment
    {
        $equipment = $this->redisClient->get("equipment:$id");

        if ($equipment === null) {
            $equipment = $this->equipmentService->getEquipment($id);
            $this->redisClient->set("equipment:$id", serialize($equipment), 'EX', $this->ttl);
        } else {
            $equipment = unserialize($equipment);
        }

        return $equipment;
    }

    public function getEquipments(): array
    {
        $equipments = $this->redisClient->get("equipments");
        if ($equipments === null) {
            $equipments = $this->equipmentService->getEquipments();
            $this->redisClient->set("equipments", serialize($equipments), 'EX', $this->ttl);
        } else {
            $equipments = unserialize($equipments);
        }

        return $equipments;
    }

}