<?php

namespace App\bootstrap;
use App\common\Infrastructure\Persistence\DBConnection;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Repository\FactionRepositoryInterface;
use App\Faction\Infrastructure\PDO\MysqlPDOFactionRepository;
use App\User\Application\Services\UserService;
use App\User\Domain\AuthenticatorInterface;
use App\User\Domain\Repository\UserReadRepositoryInterface;
use App\User\Infrastructure\Authenticator\Authenticator;
use App\User\Infrastructure\PDO\MysqlPDOUserReadRepository;
use DI\Container;
use PDO;
use Psr\Container\ContainerInterface;

/** @var Container $container */

$container->set(UserReadRepositoryInterface::class, function (ContainerInterface $container) {
    return new MysqlPDOUserReadRepository(
        $container->get(PDO::class)
    );
});

$container->set(FactionRepositoryInterface::class, function (ContainerInterface $container) {
    return new MysqlPDOFactionRepository(
        $container->get(PDO::class)
    );
});

$container->set(AuthenticatorInterface::class, function (ContainerInterface $container) {
    $config = $container->get('settings');
    return new Authenticator(
        $config['jwt']['secret'],
        $config['jwt']['issuer'],
        $config['jwt']['expires_at'],
        $container->get(UserReadRepositoryInterface::class)
    );
});

$container->set(UserService::class, function (ContainerInterface $container) {
    return new UserService(
        $container->get(UserReadRepositoryInterface::class),
        $container->get(AuthenticatorInterface::class)
    );
});

$container->set(FactionService::class, function (ContainerInterface $container) {
    return new FactionService(
        $container->get(FactionRepositoryInterface::class)
    );
});

$container->set(PDO::class, function (ContainerInterface $container) {
    $db = new DBConnection($container);
    return $db->initConnection();
});

