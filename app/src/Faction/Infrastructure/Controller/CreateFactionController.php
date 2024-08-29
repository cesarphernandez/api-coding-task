<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Controller\BaseController;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotCreatedException;
use App\Faction\Infrastructure\Validator\CreateFactionValidator;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Exception;

class CreateFactionController extends BaseController
{

    private CreateFactionValidator $validator;

    public function __construct(
        private FactionService $factionService,
    )
    {
        $this->validator = new CreateFactionValidator();
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $dto = $this->validator->validate($request);
            $created = $this->factionService->createFaction($dto);

            return JsonResponseBuilder::successRequest('success',[
                'data' => $created->toArray()
            ]);
        } catch (FactionNotCreatedException $e) {
            return JsonResponseBuilder::badRequest($e->getMessage());
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }

}