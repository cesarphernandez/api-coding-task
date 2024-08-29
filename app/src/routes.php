<?php

declare(strict_types=1);

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

        $group->group('/factions', function (RouteCollectorProxy $group) {
            $group->get('/{id}', ShowFactionController::class);
            $group->get('', ListFactionController::class);


            $group->post('', CreateFactionController::class);
            $group->delete('/{id}', DeleteFactionController::class);
        })
            ->add($authenticatorMiddleware);
    });
} catch (HttpUnauthorizedException $e) {
    return JsonResponseBuilder::badRequest('Unauthorized request');
};

