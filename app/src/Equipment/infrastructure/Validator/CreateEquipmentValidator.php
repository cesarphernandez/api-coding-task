<?php

namespace App\Equipment\infrastructure\Validator;

use App\Equipment\Application\DTO\CreateEquipmentDTO;
use Respect\Validation\Validator;
use Slim\Psr7\Request;

final class CreateEquipmentValidator
{

    public function validate(Request $request): CreateEquipmentDTO
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $nameValidator = Validator::key('name', Validator::stringType()->notEmpty()->length(1, 128));
        $typeValidator = Validator::key('type', Validator::stringType()->notEmpty());
        $madeByValidator = Validator::key('made_by', Validator::stringType()->notEmpty());

        $validator = Validator::allOf($nameValidator, $typeValidator, $madeByValidator);
        $validator->assert($data);

        return new CreateEquipmentDTO(
            $data['name'],
            $data['type'],
            $data['made_by'],
        );
    }


}