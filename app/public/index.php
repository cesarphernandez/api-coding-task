<?php

declare(strict_types=1);

use App\bootstrap\BootstrapApp;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use App\common\Application\Builder\JsonResponseBuilder;

require __DIR__ . '/../vendor/autoload.php';

$container = BootstrapApp::getInstance();
AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/../src/routes.php';

try {
    $app->run();
} catch (HttpNotFoundException $e) {
    JsonResponseBuilder::baseResponse(404, 'Not Found');
}
