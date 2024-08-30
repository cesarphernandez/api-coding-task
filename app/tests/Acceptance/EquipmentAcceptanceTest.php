<?php

namespace Acceptance;

use App\AuthorizationHelper;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class EquipmentAcceptanceTest extends TestCase
{
    protected string $token;
    protected Client $client;
    static int $equipmentId = 0;

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

    public function testCreateEquipmentSuccess()
    {
        $response = $this->client->post('/api/equipments', [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'name' => 'New Equipment',
                'made_by' => 'New Manufacturer',
                'type' => 'New Type'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('New Equipment', $responseData['data']['name']);
        self::$equipmentId = $responseData['data']['id'];
    }

    public function testGetEquipmentsSuccess()
    {
        $response = $this->client->get('/api/equipments', [
            'headers' => $this->getRequestHeaders()
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertNotEmpty($responseData['data']);
    }

    public function testGetByIdEquipmentSuccess()
    {
        $response = $this->client->get('/api/equipments/' . self::$equipmentId, [
            'headers' => $this->getRequestHeaders()
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals(self::$equipmentId, $responseData['data']['id']);
    }

    public function testUpdateEquipmentSuccess()
    {
        $response = $this->client->put('/api/equipments/' . self::$equipmentId, [
            'headers' => $this->getRequestHeaders(),
            'json' => [
                'name' => 'Updated Equipment',
                'made_by' => 'Updated Manufacturer',
                'type' => 'Updated Type'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('Updated Equipment', $responseData['data']['name']);
    }

    public function testDeleteEquipmentSuccess()
    {
        $response = $this->client->delete('/api/equipments/' . self::$equipmentId, [
            'headers' => $this->getRequestHeaders()
        ]);

        $responseData = json_decode($response->getBody(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Equipment with id ' . self::$equipmentId . ' deleted successfully.', $responseData['message']);
    }


}