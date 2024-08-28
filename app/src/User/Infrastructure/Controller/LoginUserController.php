<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Controller;

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

            return $this->JsonResponse([
                'status' => 'success',
                'token' => $token,
            ], $response, 201);
            

        } catch (NestedValidationException $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $e->getMessages()
            ]));

            return $response->withStatus(422)
                ->withHeader('Content-Type', 'application/json');
        } catch (ContainerExceptionInterface  | NotFoundExceptionInterface $e) {
            return $this->JsonResponse([
                'message' => 'Service unavailable',
            ], $response, 503);
        } catch (UserNotAuthenticate | UserNotFoundException $e) {
            return $this->JsonResponse([
                'message' => 'Bad credentials',
            ], $response, 401);
        }
    }

}