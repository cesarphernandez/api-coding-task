<?php

declare(strict_types=1);

use App\common\Application\Builder\JsonResponseBuilder;
use App\Config\BootstrapApp;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = BootstrapApp::getInstance();
AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/../src/Http/routes.php';

try {
    $app->run();
} catch (HttpNotFoundException $e) {
    JsonResponseBuilder::baseResponse(404, 'Not Found');
}
