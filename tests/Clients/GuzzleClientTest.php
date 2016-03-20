<?php

namespace Greenhouse\GreenhouseJobBoardPhp\Tests\Clients;

use Greenhouse\GreenhouseJobBoardPhp\Clients\GuzzleClient;

class JobBoardServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->baseUrl = 'https://api.greenhouse.io/v1/boards/greenhouse/embed/';
    }

    public function testGuzzleInitialize()
    {
        $client = new GuzzleClient(array('base_uri' => 'http://www.example.com'));
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseJobBoardPhp\Clients\GuzzleClient',
            $client
        );
        
        $this->assertInstanceOf('\GuzzleHttp\Client', $client->getClient());
    }
    
    public function testGetBlankUrlException()
    {
        $client = new GuzzleClient(array('base_uri' => $this->baseUrl));
        $this->expectException('\Greenhouse\GreenhouseJobBoardPhp\Clients\Exceptions\GreenhouseAPIClientException');
        $response = $client->get();
    }
    
    public function testGetException()
    {
        $errorUrl = 'https://api.greenhouse.io/v1/boards/exception_co/embed/';
        $client = new GuzzleClient(array('base_uri' => $errorUrl));
        $this->expectException('\Greenhouse\GreenhouseJobBoardPhp\Clients\Exceptions\GreenhouseAPIResponseException');
        $response = $client->get('jobs');
    }
}