<?php

namespace App\User\Infrastructure\Validator;

use App\User\Application\DTO\LoginUserDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class LoginUserValidator
{

    public function validate(Request $request): LoginUserDTO
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $emailUserValidator = Validator::key('email', Validator::stringType()->notEmpty());
        $passwordUserValidator = Validator::key('password', Validator::stringType()->notEmpty());

        $validator = Validator::allOf($emailUserValidator, $passwordUserValidator);
        $validator->assert($data);

        return new LoginUserDTO(
            $data['email'],
            $data['password'],
        );
    }

}