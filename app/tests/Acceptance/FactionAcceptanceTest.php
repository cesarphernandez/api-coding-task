<?php

namespace Acceptance;


use App\AuthorizationHelper;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class FactionAcceptanceTest extends TestCase
{

    protected string $token;
    protected Client $client;
    static int $factionId = 0;

    protected function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://localhost:8080']);
        $this->token = AuthorizationHelper::getAuthorizationToken();
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

        self::$factionId = $responseData['data']['id'];
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
        $response = $this->client->get('/api/factions/' . self::$factionId, [
            'headers' => $this->getRequestHeaders()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals(self::$factionId, $responseData['data']['id']);
    }

    public function testUpdateFactionSuccess() {

        $response = $this->client->put('/api/factions/' . self::$factionId, [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'name' => 'Updated Faction Name',
                'description' => 'Updated description of the faction'
            ]
        ]);
        $responseData = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Updated Faction Name', $responseData['data']['name']);
    }

    public function testDeleteFactionSuccess()
    {
        $response = $this->client->delete('/api/factions/' . self::$factionId, [
            'headers' => $this->getRequestHeaders()
        ]);

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Faction with id ' . self::$factionId . ' deleted successfully.', $responseData['message']);

    }


}