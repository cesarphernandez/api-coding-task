<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Controller\BaseController;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Exception;
class ListFactionController extends BaseController
{
    public function __construct(
        private FactionService $factionService,
    ){
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $factions = $this->factionService->getFactions();
            return JsonResponseBuilder::successRequest('success',[
                'data' => $factions
            ]);
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (FactionNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}