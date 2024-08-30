<?php
declare(strict_types=1);

namespace App\Config;

use DI\Container;
use Exception;

class BootstrapApp
{
    private static Container $instance;

    /**
     * @throws Exception
     */
    static private function initContainer(): Container
    {
        $config = self::getConfigFile();
        $container = new Container(require $config);
        $container->set('config', $config);

        require_once __DIR__ . '/providers.php';

        return $container;
    }


    /**
     * @throws Exception
     */
    static public function getInstance(): Container
    {
        if (!isset(self::$instance)) {
            self::$instance = self::initContainer();
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    static private function getConfigFile() {
        $config = __DIR__ . '/config.php';

        if(!file_exists($config)){
            throw new Exception('Not Found config.php file');
        }

        return $config;
    }
}