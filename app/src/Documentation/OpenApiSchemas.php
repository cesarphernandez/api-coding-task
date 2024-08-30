<?php
namespace App\Documentation;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Faction',
    required: ['id', 'name'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Faction Name'),
        new OA\Property(property: 'description', type: 'string', example: 'Description of the faction'),
    ],
    type: 'object'
)]
class OpenApiSchemas {

}