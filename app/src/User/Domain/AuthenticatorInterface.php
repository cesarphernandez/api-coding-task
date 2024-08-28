<?php

namespace App\User\Domain;

interface AuthenticatorInterface
{

    public function decode(string $token): ?User;

    public function encode(User $user): string;

}