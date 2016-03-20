<?php

namespace Greenhouse\GreenhouseJobBoardPhp\Clients;

use Greenhouse\GreenhouseJobBoardPhp\Clients\ApiClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Greenhouse\GreenhouseJobBoardPhp\Clients\Exceptions\GreenhouseAPIClientException;
use Greenhouse\GreenhouseJobBoardPhp\Clients\Exceptions\GreenhouseAPIResponseException;

/**
 * Client to wrap the Guzzle package.
 */
class GuzzleClient implements ApiClientInterface
{
    /**
     * Constructor should receive an array that would be understood by the Guzzle
     * client constructor.  Constructor hands off an unmodified array to the Guzzle
     * constructor.
     *
     * @params  $options    Array
     */
    public function __construct($options)
    {
        $this->_client = new Client($options);
    }
    
    /**
     * Fetch the URL. As this is guzzle, this can take a relative URL.  See the Guzzle
     * docs more info.
     *
     * @params  string  $url        A relative URL off the base URL. A full URL 
     *                                  should work, too
     * @return  string  The Raw JSON response from Greenhouse
     * @throws  GreenhouseAPIClientException if URL is blank.
     * @raise   GreenhouseAPIResponseException  if the get request fails
     */
    public function get($url="")
    {
        if (empty($url)) {
            throw new GreenhouseAPIClientException('Url must be set for get method.');
        }
        
        try {
            $guzzleResponse = $this->_client->request('GET', $url);
        } catch (RequestException $e) {
            throw new GreenhouseAPIResponseException($e->getMessage());
        }
        
        return $guzzleResponse->getBody();
    }
    
    public function post()
    {
    
    }

    public function getClient()
    {
        return $this->_client;
    }
}