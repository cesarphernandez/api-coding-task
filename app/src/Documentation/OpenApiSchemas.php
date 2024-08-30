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

#[OA\Schema(
    schema: 'Equipment',
    description: 'Equipment entity',
    required: ['name', 'type', 'madeBy'],
    properties: [
        new OA\Property(property: 'id', description: 'ID of the equipment', type: 'integer', example: 1),
        new OA\Property(property: 'name', description: 'Name of the equipment', type: 'string', example: 'Excavator'),
        new OA\Property(property: 'type', description: 'Type of the equipment', type: 'string', example: 'Heavy Machinery'),
        new OA\Property(property: 'madeBy', description: 'Manufacturer of the equipment', type: 'string', example: 'Caterpillar'),
    ],
    type: 'object'
)]
class OpenApiSchemas {

}