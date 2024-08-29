<?php
declare(strict_types=1);
namespace App\User\Infrastructure\Middleware;

use App\common\Application\Builder\JsonResponseBuilder;
use App\User\Domain\AuthenticatorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

class AuthenticatorMiddleware
{

    public function __construct(private AuthenticatorInterface $authenticator)
    {

    }

    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader) {
            return JsonResponseBuilder::unauthorizedRequest('Unauthorized request');
        }
        $token = str_replace('Bearer ', '', $authHeader);
        $user = $this->authenticator->decode($token);
        if (!$user) {
            return JsonResponseBuilder::unauthorizedRequest('Unauthorized request');
        }

        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }

}