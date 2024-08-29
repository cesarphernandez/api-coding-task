<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Controller\BaseController;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Exception;

class ShowFactionController extends BaseController
{
    private IdValidator $validator;

    public function __construct(
        private FactionService $factionService,
    ){
        $this->validator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args ): Response
    {
        try {
            $dto = $this->validator->validate($args);

            $faction = $this->factionService->getFaction($dto->getId());
            return JsonResponseBuilder::successRequest('success',[
                'data' => $faction->toArray()
            ]);
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::unavailableRequest('Service unavailable');
        } catch (FactionNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}