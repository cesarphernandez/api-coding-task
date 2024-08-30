<?php

declare(strict_types=1);

use App\common\Infrastructure\Controller\DocumentationController;
use App\Equipment\infrastructure\Controller\CreateEquipmentController;
use App\Equipment\infrastructure\Controller\DeleteEquipmentController;
use App\Equipment\infrastructure\Controller\ListEquipmentController;
use App\Equipment\infrastructure\Controller\ShowEquipmentController;
use App\Equipment\infrastructure\Controller\UpdateEquipmentController;
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
        $group->get('/documentation', DocumentationController::class);
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

        $group->group('/equipments', function (RouteCollectorProxy $equipmentGroup) {
            $equipmentGroup->get('/{id:[0-9]+}', ShowEquipmentController::class);
            $equipmentGroup->get('', ListEquipmentController::class);

            $equipmentGroup->group('', function (RouteCollectorProxy $equipmentGroupAuthorization) {
                $equipmentGroupAuthorization->post('', CreateEquipmentController::class);
                $equipmentGroupAuthorization->put('/{id:[0-9]+}', UpdateEquipmentController::class);
                $equipmentGroupAuthorization->delete('/{id:[0-9]+}', DeleteEquipmentController::class);
            })->add(new AuthorizationMiddleware(['admin']));
        })->add($authenticatorMiddleware);
    });
} catch (HttpUnauthorizedException $e) {
    return JsonResponseBuilder::badRequest('Unauthorized request');
};

