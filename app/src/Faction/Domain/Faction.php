<?php

namespace App\Faction\Domain;

class Faction
{
    public function __construct(
        private string $name,
        private string $description,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description
        ];
    }

}