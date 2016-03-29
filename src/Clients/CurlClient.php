<?php

namespace Greenhouse\GreenhouseToolsPhp\Clients;

use Greenhouse\GreenhouseToolsPhp\Clients\ApiClientInterface;
use Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIClientException;
use Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIResponseException;

/**
 * Client to wrap libcurl
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
     * Fetch the URL. 
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
     * @throws  GreenhouseAPIClientException    if $postParameters contains an array(), indicating multiselect.
     */
    public function post(Array $postVars, Array $headers, $url)
    {
        // Use this to check for multi-selects.
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
     * Greenhouse's POST format matches CURL.  However, it will reject any attempt to POST an array because
     * libcurl has a bug that prevents submitting it correctly to non-PHP applications.  For more details, see
     * the PHP bug here: https://bugs.php.net/bug.php?id=51634
     * This will check for multiselects, throw an exception if one exists, or just reflect the parameters.
     *
     * @params  Array   $postParameters
     * @return  Array
     * @throws  GreenhouseAPIClientException    if $postParameters contains an array(), indicating multiselect.
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