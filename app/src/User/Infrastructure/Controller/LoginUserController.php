<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Controller;

use App\common\Application\Builder\JsonResponseBuilder;
use App\User\Application\Services\UserService;
use App\User\Domain\Exception\UserNotAuthenticate;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Infrastructure\Validator\LoginUserValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/login',
    summary: 'Autenticar usuario y obtener token',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['username', 'password'],
            properties: [
                new OA\Property(property: 'username', type: 'string', example: 'user@example.com'),
                new OA\Property(property: 'password', type: 'string', example: 'password123')
            ]
        )
    ),
    tags: ['Users'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Autenticación exitosa',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIsInR...')
                ]
            )
        ),
        new OA\Response(
            response: 422,
            description: 'Error en los datos de entrada',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Invalid input data')
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: 'Usuario no encontrado o no autenticado',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'User not found or not authenticated')
                ]
            )
        ),
        new OA\Response(
            response: 503,
            description: 'Servicio no disponible',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'error'),
                    new OA\Property(property: 'message', type: 'string', example: 'Service unavailable')
                ]
            )
        )
    ]
)]
class LoginUserController
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

            return JsonResponseBuilder::successRequest('success', [
                    'token' => $token
            ]);
        } catch (NestedValidationException $e) {
            return JsonResponseBuilder::unprocessableEntityRequest($e->getMessage());
        } catch (ContainerExceptionInterface  | NotFoundExceptionInterface $e) {
            return JsonResponseBuilder::unavailableRequest('Service unavailable');
        } catch (UserNotAuthenticate | UserNotFoundException $e) {
            return JsonResponseBuilder::notFoundRequest($e->getMessage());
        }
    }

}