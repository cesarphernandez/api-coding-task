<?php

namespace App\Equipment\Domain;

class Equipment
{

    public function __construct(
        private string $name,
        private string $type,
        private string $madeBy,
        private int $id = 0,
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'madeBy' => $this->madeBy,
        ];
    }

}