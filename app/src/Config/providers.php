<?php

namespace App\bootstrap;
use App\common\Infrastructure\Persistence\DBConnection;
use App\common\Infrastructure\Persistence\RedisConnection;
use App\Equipment\Application\Interfaces\EquipmentServiceInterface;
use App\Equipment\Application\Services\CacheEquipmentService;
use App\Equipment\Application\Services\EquipmentService;
use App\Equipment\Domain\Repository\EquipmentRepositoryInterface;
use App\Equipment\infrastructure\PDO\MysqlPDOEquipmentRepository;
use App\Faction\Application\Interfaces\FactionServiceInterface;
use App\Faction\Application\Services\CacheFactionService;
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
use Predis\Client;
use Psr\Container\ContainerInterface;

/** @var Container $container */

$container->set(PDO::class, function (ContainerInterface $container) {
    $db = new DBConnection($container);
    return $db->initConnection();
});

$container->set(Client::class, function (ContainerInterface $container) {
    $redis = new RedisConnection($container);
    return $redis->initConnection();
});

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

$container->set(EquipmentRepositoryInterface::class, function (ContainerInterface $container) {
    return new MysqlPDOEquipmentRepository(
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

$container->set(EquipmentService::class, function (ContainerInterface $container) {
    return new EquipmentService(
        $container->get(EquipmentRepositoryInterface::class)
    );
});

$container->set(EquipmentServiceInterface::class, function (ContainerInterface $container) {
    $service = $container->get(EquipmentService::class);
    return new CacheEquipmentService(
        $service,
        $container->get(Client::class),
        $container->get('settings')['redis']['ttl']
    );
});

$container->set(FactionService::class, function (ContainerInterface $container) {
    return new FactionService(
        $container->get(FactionRepositoryInterface::class)
    );
});

$container->set(FactionServiceInterface::class, function (ContainerInterface $container) {
    $service = $container->get(FactionService::class);
    return new CacheFactionService(
        $service,
        $container->get(Client::class),
        $container->get('settings')['redis']['ttl']
    );
});

