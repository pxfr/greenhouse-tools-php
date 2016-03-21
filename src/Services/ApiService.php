<?php

namespace Greenhouse\GreenhouseJobBoardPhp\Services;

use Greenhouse\GreenhouseJobBoardPhp\Services\ApiService;
use Greenhouse\GreenhouseJobBoardPhp\Services\Exceptions\GreenhouseServiceException;

class ApiService
{
    protected $_apiClient;
    
    public function setClient($apiClient)
    {
        $this->_apiClient = $apiClient;
    }
    
    public function jobBoardBaseUrl($clientToken)
    {
        return "https://api.greenhouse.io/v1/boards/{$clientToken}/embed/";
    }
    
    public function getJobBoardBaseUrl()
    {
        if empty($this->_clientToken) {
            raise new GreenhouseServiceException('A client token must be defined to get the base URL.');
        }
        return self::jobBoardBaseUrl($this->_clientToken);
    }
    
    public function getClient()
    {
        return $this->_apiClient;
    }
}
