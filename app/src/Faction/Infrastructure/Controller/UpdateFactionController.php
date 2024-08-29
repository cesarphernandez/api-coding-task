<?php

namespace App\Faction\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Controller\BaseController;
use App\common\Infrastructure\Validator\IdValidator;
use App\Faction\Application\Services\FactionService;
use App\Faction\Domain\Exceptions\FactionNotFoundException;
use App\Faction\Infrastructure\Validator\UpdateFactionValidator;
use Exception;
use InvalidArgumentException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UpdateFactionController extends BaseController
{
    private UpdateFactionValidator $validator;
    private IdValidator $idValidator;

    public function __construct(
        private FactionService $factionService,
    )
    {
        $this->validator = new UpdateFactionValidator();
        $this->idValidator = new IdValidator();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $dto = $this->validator->validate($request);
            $id = $this->idValidator->validate($args);
            $updated = $this->factionService->updateFaction($id->getId(), $dto);

            return JsonResponseBuilder::successRequest('success', [
                'data' => $updated->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (FactionNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        } catch (Exception) {
            return JsonResponseBuilder::internalServerErrorRequest('Internal server error');
        }
    }


}