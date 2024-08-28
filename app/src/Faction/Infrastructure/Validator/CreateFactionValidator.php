<?php

declare(strict_types=1);

namespace App\Faction\Infrastructure\Validator;

use App\Faction\Application\DTO\CreateFactionDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

final class CreateFactionValidator
{
    /**
     * Validate incoming request data and map to DTO for creating a faction.
     *
     * @param Request $request
     * @return CreateFactionDTO
     * @throws NestedValidationException
     */
    public function validate(Request $request): CreateFactionDTO
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $factionNameValidator = Validator::key('faction_name', Validator::stringType()->notEmpty()->length(1, 128));
        $descriptionValidator = Validator::key('description', Validator::stringType()->notEmpty());

        $validator = Validator::allOf($factionNameValidator, $descriptionValidator);
        $validator->assert($data);

        return new CreateFactionDTO(
            $data['faction_name'],
            $data['description'],
        );
    }
}
