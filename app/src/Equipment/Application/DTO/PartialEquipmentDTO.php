<?php

namespace App\Equipment\Application\DTO;

final class PartialEquipmentDTO
{
    private ?string $name;
    private ?string $type;
    private ?string $madeBy;

    public function __construct(?string $name = null, ?string $type = null, ?string $madeBy = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->madeBy = $madeBy;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getMadeBy(): ?string
    {
        return $this->madeBy;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function hasMadeBy(): bool
    {
        return $this->madeBy !== null;
    }

}