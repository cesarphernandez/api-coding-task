<?php

namespace App\User\Domain\Exception;

use Exception;

class UserNotAuthenticate extends Exception
{
    private const DETAIL_NOT_AUTHENTICATE = 'Bad credentials for user with email %s';

    public static function fromEmail(string $email): self
    {
        return new self(sprintf(self::DETAIL_NOT_AUTHENTICATE, $email));

    }
}