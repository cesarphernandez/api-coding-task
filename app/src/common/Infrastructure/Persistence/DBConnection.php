<?php

namespace App\common\Infrastructure\Persistence;
use DI\Container;
use PDO;
use PDOException;

class DBConnection
{

    private array $settingsDb;

    public function __construct(
        private Container $container
    ) {
        $this->settingsDb = $this->container->get('settings')['db'];
    }


    public  function initConnection(): \PDO
    {
        $host = $this->settingsDb['host'];
        $db = $this->settingsDb['database'];
        $user = $this->settingsDb['username'];
        $pass = $this->settingsDb['password'];

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            return new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

    }
}