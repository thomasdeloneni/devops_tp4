<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ApiTest extends TestCase
{
    private $client;
    private $baseUri = 'http://localhost:8080';

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'http_errors' => false
        ]);
    }

    public function testHomeEndpoint()
    {
        $response = $this->client->get('/');
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('success', $data['status']);
    }

    public function testHealthEndpoint()
    {
        $response = $this->client->get('/health');
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('healthy', $data['status']);
        $this->assertEquals('demo-app', $data['service']);
    }

    public function testInfoEndpoint()
    {
        $response = $this->client->get('/info');
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('hostname', $data);
        $this->assertArrayHasKey('php_version', $data);
        $this->assertArrayHasKey('environment', $data);
        $this->assertArrayHasKey('system', $data);
    }
}
