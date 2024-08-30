<?php

namespace App\Equipment\infrastructure\Validator;

use App\Equipment\Application\DTO\PartialEquipmentDTO;
use InvalidArgumentException;
use Respect\Validation\Validator;
use Slim\Psr7\Request;

final class UpdateEquipmentValidator
{
    public function validate(Request $request): PartialEquipmentDTO
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        if (empty($data['name']) && empty($data['type']) && empty($data['made_by'])) {
            throw new InvalidArgumentException("At least one of 'name', 'type' or 'made_by' must be provided.");
        }

        if (isset($data['name'])) {
            Validator::stringType()->length(1, 128)->assert($data['name']);
        }

        if (isset($data['type'])) {
            Validator::stringType()->notEmpty()->assert($data['type']);
        }

        if (isset($data['made_by'])) {
            Validator::stringType()->notEmpty()->assert($data['made_by']);
        }

        return new PartialEquipmentDTO(
            $data['name'] ?? null,
            $data['type'] ?? null,
            $data['made_by'] ?? null
        );
    }

}