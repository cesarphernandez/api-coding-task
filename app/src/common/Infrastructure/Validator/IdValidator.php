<?php

namespace App\common\Infrastructure\Validator;

use App\common\Application\IdDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class IdValidator
{
    /**
     * Validate incoming request data and map to DTO for creating a faction.
     *
     * @param array $args
     * @return IdDTO
     */
    public function validate(array $args): IdDTO
    {
        $args['id'] = (int) $args['id'];
        $idValidator = Validator::key('id', Validator::intType()->notEmpty());

        $validator = Validator::allOf($idValidator);
        $validator->assert($args);
        return new IdDTO(
            $args['id'],
        );
    }

}