<?php

namespace Greenhouse\GreenhouseToolsPhp\Tests\Clients;

use Greenhouse\GreenhouseToolsPhp\Clients\CurlClient;

class CurlClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client       = new CurlClient();
        $this->resumePath   = realpath(dirname(__FILE__)) . '/../files/documents/test_resume.docx';
    }

    public function testGuzzleInitialize()
    {
        $client = new CurlClient(array('base_uri' => 'http://www.example.com'));
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseToolsPhp\Clients\CurlClient',
            $client
        );
    }
    
    public function testGetException()
    {
        $errorUrl = 'https://api.greenhouse.io/v1/boards/exception_co/embed/';
        $client = new CurlClient();
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIResponseException');
        $response = $client->get($errorUrl);
    }
    
    public function testFormatPostParameters()
    {
        $postVars = array(
            'first_name' => 'Hiram',
            'last_name' => 'Abiff',
        );

        $this->assertEquals($postVars, $this->client->formatPostParameters($postVars));
    }
    
    public function testFormatPostParametersException()
    {
        $postVars = array(
            'first_name' => 'Hiram',
            'last_name' => 'Abiff',
            'talents' => array('building', 'things', 'and', 'stuff')
        );

        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIClientException');
        $this->client->formatPostParameters($postVars);
    }
    
    public function testPostThrowsExceptionsWithArrayValues()
    {
        $postVars = array(
            'first_name' => 'Hiram',
            'last_name' => 'Abiff',
            'talents' => array('building', 'things', 'and', 'stuff')
        );

        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIClientException');
        $this->client->post($postVars, array(), 'http://www.example.com');
    }
}