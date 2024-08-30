<?php

namespace Acceptance;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class LoginAcceptanceTest extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8080',
            'http_errors' => false,
        ]);
    }

    public function testLoginSuccess()
    {
        $response = $this->client->post('/api/login', [
            'json' => [
                'email' => 'admin@example.com',
                'password' => 'password1234'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('token', $responseData);
    }

    public function testLoginFailed()
    {
        try {
            $response = $this->client->post('/api/login', [
                'json' => [
                    'password' => 'test',
                    'email' => 'admin@example.com'
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        $responseData = json_decode($response->getBody(), true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Bad credentials for user with email admin@example.com', $responseData['message']);
    }
}