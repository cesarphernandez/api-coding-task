<?php

namespace Acceptance;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

abstract class BaseTestCase extends TestCase
{
    protected string $token;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client(['base_uri' => 'http://localhost:8080']);
        $this->authenticate();
    }

    private function authenticate(): void
    {
        $response = $this->client->post('/api/login', [
            'json' => [
                'email' => 'admin@example.com',
                'password' => 'password123'
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);
        $this->token = $responseData['token'];
    }

    protected function getRequestHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ];
    }
}
