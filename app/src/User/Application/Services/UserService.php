<?php

namespace App\User\Application\Services;

use App\User\Application\DTO\LoginUserDTO;
use App\User\Domain\AuthenticatorInterface;
use App\User\Domain\Exception\UserNotAuthenticate;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserReadRepositoryInterface;
use PDOException;

class UserService
{

    public function __construct(
        private UserReadRepositoryInterface $userReadRepository,
        private AuthenticatorInterface $authenticator
    ){
    }

    /**
     * @throws UserNotFoundException
     * @throws UserNotAuthenticate
     */
    public function login(LoginUserDTO $userDTO): string
    {
        try {
            $user = $this->userReadRepository->getDetailUser($userDTO->getEmail());
            if (!$user->authenticate($userDTO->getPassword())) {
                throw new UserNotAuthenticate($userDTO->getEmail());
            }
            return $this->authenticator->encode($user);

        } catch (PDOException) {
            throw new UserNotFoundException($userDTO->getEmail());
        }

    }

}