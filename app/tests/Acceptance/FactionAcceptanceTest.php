<?php

namespace Acceptance;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class FactionAcceptanceTest extends TestCase
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
                'password' => 'password1234'
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

    public function testCreateFactionSuccess()
    {
        $response = $this->client->post('/api/factions', [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'faction_name' => 'New Faction',
                'description' => 'Description of the new faction'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('New Faction', $responseData['data']['name']);

        $deleteResponse = $this->client->delete('/api/factions/' . $responseData['data']['id'], [
            'headers' => $this->getRequestHeaders()
        ]);
        $this->assertEquals(200, $deleteResponse->getStatusCode());
    }

    public function testGetFactionsSuccess()
    {
        $response = $this->client->get('/api/factions', [
            'headers' => $this->getRequestHeaders()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertNotEmpty($responseData['data']);
    }

    public function testGetByIdFactionSuccess()
    {
        $response = $this->client->get('/api/factions/2', [
            'headers' => $this->getRequestHeaders()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('2', $responseData['data']['id']);
    }

    public function testDeleteFactionSuccess()
    {
        $createdFaction = $this->client->post('/api/factions', [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'faction_name' => 'New Faction',
                'description' => 'Description of the new faction'
            ]
        ]);
        $responseDataCreation = json_decode($createdFaction->getBody(), true);
        $id = $responseDataCreation['data']['id'];


        $response = $this->client->delete('/api/factions/' . $id, [
            'headers' => $this->getRequestHeaders()
        ]);

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Faction with id ' . $id . ' deleted successfully.', $responseData['message']);

    }

    public function testUpdateFactionSuccess() {
        $beforeFaction = $this->client->get('/api/factions/2', [
            'headers' => $this->getRequestHeaders()
        ]);
        $beforeData = json_decode($beforeFaction->getBody(), true);


        $response = $this->client->put('/api/factions/2', [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'name' => 'Updated Faction Name',
                'description' => 'Updated description of the faction'
            ]
        ]);
        $responseData = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Updated Faction Name', $responseData['data']['name']);

        $response = $this->client->put('/api/factions/2', [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'name' => $beforeData['data']['name'],
                'description' => $beforeData['data']['description']
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($beforeData['data']['name'], $responseData['data']['name']);
    }

}