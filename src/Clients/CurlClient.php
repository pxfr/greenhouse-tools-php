<?php

namespace Greenhouse\GreenhouseToolsPhp\Clients;

use Greenhouse\GreenhouseToolsPhp\Clients\ApiClientInterface;
use Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIClientException;
use Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIResponseException;

/**
 * Client to wrap the Guzzle package.
 */
class CurlClient implements ApiClientInterface
{
    private $_client;
    
    /**
     * Constructor should receive an array that would be understood by the Guzzle
     * client constructor.  Constructor hands off an unmodified array to the Guzzle
     * constructor.
     *
     * @params  $options    Array
     */
    public function __construct()
    {
        $this->_client = curl_init();
        curl_setopt($this->_client, CURLOPT_RETURNTRANSFER, 1);
    }
    
    /**
     * Fetch the URL. As this is guzzle, this can take a relative URL.  See the Guzzle
     * docs more info.
     *
     * @params  string  $url        A full URL to get.
     * @return  string  The Raw JSON response from Greenhouse
     * @throws  GreenhouseAPIResponseException  if the get request fails
     */
    public function get($url)
    {
        curl_setopt($this->_client, CURLOPT_URL, $url);
        curl_setopt($this->_client, CURLOPT_POST, 0);
        return $this->_execute();
    }
    
    /**
     * Post to the application endpoint.
     * 
     * @params  Array   $postVars       A guzzle compliant multipart array of post parameters.
     * @params  Array   $headers        This should only contain the Authorization header.
     * @params  string  $url            This can be left blank.  Url is set in the constructor.
     * @return  string
     * @throws  GreenhouseAPIResponseException  for non-200 responses
     */
    public function post(Array $postVars, Array $headers, $url)
    {
        $this->formatPostParameters($postVars);
        
        curl_setopt($this->_client, CURLOPT_URL, $url);
        curl_setopt($this->_client, CURLOPT_POST, 1);
        curl_setopt($this->_client, CURLOPT_POSTFIELDS, $postVars);
        curl_setopt($this->_client, CURLOPT_HTTPHEADER, $headers);
        
        return $this->_execute();
    }
    
    private function _execute()
    {
        $response = curl_exec($this->_client);
        $httpCode = curl_getinfo($this->_client, CURLINFO_HTTP_CODE);
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new GreenhouseApiResponseException("$httpCode -- $response");
        }
        return $response;
    }
    
    /**
     * Return a Guzzle post parameter array that can be entered in to the 'multipart'
     * argument of a post request.  For details on this, see the Guzzle
     * documentation here: http://docs.guzzlephp.org/en/latest/request-options.html#multipart
     *
     * @params  Array   $postParameters
     * @return  Array
     */
    public function formatPostParameters(Array $postParameters=array())
    {
        foreach ($postParameters as $key => $value) {
            if (is_array($value)) {
                throw new GreenhouseAPIClientException('CurlClient does not support array post parameters.');
            }
        }
        
        return $postParameters;
    }

    public function getClient()
    {
        return $this->_client;
    }
}