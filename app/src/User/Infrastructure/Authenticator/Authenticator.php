<?php

namespace App\User\Infrastructure\Authenticator;

use App\User\Domain\AuthenticatorInterface;
use App\User\Domain\Repository\UserReadRepositoryInterface;
use App\User\Domain\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Authenticator implements AuthenticatorInterface
{
    private const ALGORITHM = 'HS256';
    public function __construct(
        private string $secret,
        private string $issuer,
        private int $expiresAt,
        private UserReadRepositoryInterface $userRepository,
    )
    {
    }

    public function decode(string $token): ?User
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, self::ALGORITHM));
            return $this->userRepository->getDetailUser($decoded->email);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function encode(User $user): string
    {
        $data = [
            'iss' => $this->issuer,
            'iat' => time(),
            'exp' => time() + $this->expiresAt,
            'email' => $user->getEmail()
        ];
        return JWT::encode($data, $this->secret, self::ALGORITHM);
    }
}