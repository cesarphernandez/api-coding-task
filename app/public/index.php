<?php

declare(strict_types=1);

use App\bootstrap\BootstrapApp;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = BootstrapApp::getInstance();
AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/../src/routes.php';

$app->run();