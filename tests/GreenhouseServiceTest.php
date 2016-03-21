<?php

namespace Greenhouse\GreenhouseJobBoardPhp\Tests;

use Greenhouse\GreenhouseJobBoardPhp\GreenhouseService;

class GreenhouseServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->apiKey       = 'testapikey';
        $this->boardToken   = 'test_token';
        $this->greenhouseService = new GreenhouseService(array(
            'apiKey'    => $this->apiKey,
            'boardToken'=> $this->boardToken
        ));
    }
    
    public function testGetJobBoardService()
    {
        $service = $this->greenhouseService->getJobBoardService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseJobBoardPhp\Services\JobBoardService',
            $service
        );
        $this->assertContains($this->boardToken, $service->scriptTag());
    }
    
    public function testGetJobApiService()
    {
        $service = $this->greenhouseService->getJobApiService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseJobBoardPhp\Services\JobApiService',
            $service
        );
        
        $this->assertEquals(
            'https://api.greenhouse.io/v1/boards/test_token/embed/',
            $service->getJobBoardBaseUrl()
        );
        
        $this->assertInstanceOf('\GuzzleHttp\Client', $service->getClient());
    }
    
    public function testGetJobBoardService()
    {
        $service = $this->greenhouseService->getJobBoardService();
        $this->assertInstanceOf(
            '\Greenhouse\GreenhouseJobBoardPhp\Services\JobBoardService',
            $service
        );
        $this->assertContains($this->boardToken, $service->scriptTag());
    }

}

/**

<?php

namespace Greenhouse\GreenhouseJobBoardPhp;

class GreenhouseService
{
    private $_apiKey;
    private $_boardToken;
    
    public function __construct($options=array())
    {
        $this->_apiKey = $options['apiKey'];
        $this->_boardToken = $options['boardToken'];
    }
    
    public function getApiService($boardToken='')
    {
    
    }
    
    public function getApplicationService($apiKey='')
    {
    
    }
    
    public function getJobBoardService()
    {
        return new \Greenhouse\GreenhouseJobBoardPhp\Services\JobBoardService($this->_boardToken);
    }
    
    public function getFormService()
    {
    
    }
}**/