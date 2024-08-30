<?php

namespace App\common\Infrastructure\Controller;

use Slim\Psr7\Response;

class DocumentationController
{
    public function __invoke()
    {
        include __DIR__ . '/../../../Http/Views/Documentation.php';
        return new Response(200);
    }

}