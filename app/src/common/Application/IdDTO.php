<?php

namespace App\common\Application;

final class IdDTO
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

}