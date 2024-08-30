<?php

namespace Unit\Application\DTO;

use App\User\Application\DTO\LoginUserDTO;
use PHPUnit\Framework\TestCase;

class LoginUserDTOTest extends TestCase
{
    public function testDTOCreation()
    {
        $dto = new LoginUserDTO('test@example.com', 'password');

        $this->assertEquals('password', $dto->getPassword());
        $this->assertEquals('test@example.com', $dto->getEmail());
    }
}