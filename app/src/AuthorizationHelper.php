<?php

namespace App;

use GuzzleHttp\Client;

class AuthorizationHelper
{
    static string $token = "";

    public static function getAuthorizationToken(): string
    {
        if (self::$token !== "") {
            return self::$token;
        }
        $client = new Client([
            'base_uri' => 'http://localhost:8080',
            'http_errors' => false,
        ]);
        $response = $client->post('/api/login', [
            'json' => [
                'email' => 'admin@example.com',
                'password' => 'password1234'
            ]
        ]);
        $responseData = json_decode($response->getBody(), true);
        self::$token = $responseData['token'];
        return self::$token;
    }

}