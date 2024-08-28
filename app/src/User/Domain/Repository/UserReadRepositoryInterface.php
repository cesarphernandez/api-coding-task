<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\User;

interface UserReadRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function getDetailUser(string $email): User;

}