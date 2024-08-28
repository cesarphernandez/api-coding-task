<?php

namespace App\User\Infrastructure\PDO;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Factory\UserFromMysqlFactory;
use App\User\Domain\Repository\UserReadRepositoryInterface;
use App\User\Domain\User;
use PDO;

class MysqlPDOUserReadRepository implements UserReadRepositoryInterface
{
    private UserFromMysqlFactory $factory;
    public function __construct(
        private PDO $connection
    ) {
        $this->factory = new UserFromMysqlFactory();
    }

    /**
     * @throws UserNotFoundException
     */
    public function getDetailUser(string $email): User
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() === 0) {
            throw UserNotFoundException::fromEmailNotFound($email);
        }

        $result =  $stmt->fetch();
        return $this->factory->createDetailUser($result);

    }

}