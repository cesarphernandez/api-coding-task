<?php

namespace App\Equipment\Application\DTO;

final class CreateEquipmentDTO
{
    public function __construct(
        private string $name,
        private string $type,
        private string $madeBy
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMadeBy(): string
    {
        return $this->madeBy;
    }

}