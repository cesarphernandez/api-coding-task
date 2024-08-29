<?php

namespace App\Faction\Application\DTO;

final class PartialFactionDTO
{
    private ?string $name;
    private ?string $description;

    public function __construct(?string $name = null, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }
}