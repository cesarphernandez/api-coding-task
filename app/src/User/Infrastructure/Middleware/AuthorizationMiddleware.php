<?php

namespace App\User\Infrastructure\Middleware;

use App\common\Application\Builder\JsonResponseBuilder;
use App\User\Domain\User;

class AuthorizationMiddleware
{
    public function __construct(
        private array $roles
    ) {
    }

    public function __invoke($request, $handler)
    {
        /** @var User $user */
        $user = $request->getAttribute('user');
        if (!$user->hasRoles($this->roles)) {
            return JsonResponseBuilder::unauthorizedRequest('Unauthorized request');
        }
        return $handler->handle($request);
    }

}