<?php

namespace App\User\Domain\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    private const DETAIL_NOT_FOUND = 'The user with email %s does not exist';

    public static function fromEmailNotFound(string $email): self
    {
        return new self(sprintf(self::DETAIL_NOT_FOUND, $email));
    }

}