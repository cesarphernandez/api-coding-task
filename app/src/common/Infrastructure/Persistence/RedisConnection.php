<?php
declare(strict_types=1);

namespace App\common\Infrastructure\Persistence;

use DI\Container;
use Predis\Client;

class RedisConnection
{
    private array $settingsRedis;

    public function __construct(
        private Container $container
    ) {
        $this->settingsRedis = $this->container->get('settings')['redis'];
    }

    public function initConnection(): Client
    {
        $host = $this->settingsRedis['host'];
        $port = $this->settingsRedis['port'];
        $scheme = $this->settingsRedis['scheme'];

        return new Client([
            'scheme' => $scheme,
            'host' => $host,
            'port' => $port,
        ]);
    }
}