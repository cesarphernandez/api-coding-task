<?php

namespace App\User\Domain\Factory;

use App\User\Domain\User;

class UserFromMysqlFactory
{

    public function createDetailUser(array $user): User
    {
        return new User(
            $user['email'],
            $user['password'],
            json_decode($user['roles'], true)
        );
    }

}