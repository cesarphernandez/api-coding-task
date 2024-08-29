<?php

declare(strict_types=1);

use App\Faction\Infrastructure\Controller\UpdateFactionController;
use App\User\Infrastructure\Middleware\AuthorizationMiddleware;
use Slim\App;

use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteCollectorProxy;

use App\Faction\Infrastructure\Controller\CreateFactionController;
use App\Faction\Infrastructure\Controller\DeleteFactionController;
use App\Faction\Infrastructure\Controller\ShowFactionController;
use App\Faction\Infrastructure\Controller\ListFactionController;
use App\User\Infrastructure\Controller\LoginUserController;
use App\User\Infrastructure\Middleware\AuthenticatorMiddleware;
use App\common\Application\Builder\JsonResponseBuilder;

/**
 * @var App $app
 */

try {
    $app->group('/api', function (RouteCollectorProxy $group)  {
        $group->post('/login', LoginUserController::class);
        $authenticatorMiddleware = $this->get(AuthenticatorMiddleware::class);

        $group->group('/factions', function (RouteCollectorProxy $factionsGroup) {
            $factionsGroup->get('/{id:[0-9]+}', ShowFactionController::class);
            $factionsGroup->get('', ListFactionController::class);

            $factionsGroup->group('', function (RouteCollectorProxy $factionsGroupAuthorization) {
                $factionsGroupAuthorization->post('', CreateFactionController::class);
                $factionsGroupAuthorization->put('/{id:[0-9]+}', UpdateFactionController::class);
                $factionsGroupAuthorization->delete('/{id:[0-9]+}', DeleteFactionController::class);
            })->add(new AuthorizationMiddleware(['admin']));

        })->add($authenticatorMiddleware);
    });
} catch (HttpUnauthorizedException $e) {
    return JsonResponseBuilder::badRequest('Unauthorized request');
};

