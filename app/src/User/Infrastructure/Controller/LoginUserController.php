<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\common\Infrastructure\Controller\BaseController;
use App\User\Application\Services\UserService;
use App\User\Domain\Exception\UserNotAuthenticate;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Infrastructure\Validator\LoginUserValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class LoginUserController extends BaseController
{
    private LoginUserValidator $validator;

    public function __construct(
        private UserService $userService,
    )
    {
        $this->validator = new LoginUserValidator();

    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $dto = $this->validator->validate($request);
            $token = $this->userService->login($dto);

            return JsonResponseBuilder::successRequest('success',
                [
                    'token' => $token
                ]
            );
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (ContainerExceptionInterface  | NotFoundExceptionInterface $e) {
            return JsonResponseBuilder::unavailableRequest('Service unavailable');
        } catch (UserNotAuthenticate | UserNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        }
    }

}