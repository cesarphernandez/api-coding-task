<?php

namespace App\Faction\Infrastructure\Validator;

use App\Faction\Application\DTO\PartialFactionDTO;
use InvalidArgumentException;
use Respect\Validation\Validator;
use Slim\Psr7\Request;

final class UpdateFactionValidator
{
    public function validate(Request $request): PartialFactionDTO {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        if (empty($data['name']) && empty($data['description'])) {
            throw new InvalidArgumentException("At least one of 'name' or 'description' must be provided.");
        }

        if (isset($data['name'])) {
            Validator::stringType()->length(1, 128)->assert($data['name']);
        }

        if (isset($data['description'])) {
            Validator::stringType()->notEmpty()->assert($data['description']);
        }

        return new PartialFactionDTO(
            $data['name'] ?? null,
            $data['description'] ?? null
        );

    }

}