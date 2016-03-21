<?php

namespace Greenhouse\GreenhouseJobBoardPhp\Services;

use Greenhouse\GreenhouseJobBoardPhp\Services\ApiService;

class ApiService
{
    protected $_apiClient;
    
    public function setClient($apiClient)
    {
        $this->_apiClient = $apiClient;
    }
    
    public static function jobBoardBaseUrl($clientToken)
    {
        return "https://api.greenhouse.io/v1/boards/{$clientToken}/embed/";
    }
}
