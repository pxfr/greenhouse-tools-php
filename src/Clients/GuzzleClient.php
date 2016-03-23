<?php

namespace Greenhouse\GreenhouseToolsPhp\Clients;

use Greenhouse\GreenhouseToolsPhp\Clients\ApiClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIClientException;
use Greenhouse\GreenhouseToolsPhp\Clients\Exceptions\GreenhouseAPIResponseException;

/**
 * Client to wrap the Guzzle package.
 */
class GuzzleClient implements ApiClientInterface
{
    private $_client;
    
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
     * @throws  GreenhouseAPIResponseException  if the get request fails
     */
    public function get($url="")
    {
        try {
            $guzzleResponse = $this->_client->request('GET', $url);
        } catch (RequestException $e) {
            throw new GreenhouseAPIResponseException($e->getMessage());
        }
        
        /**
         * Just return the response cast as a string.  The rest of the universe need
         * not be aware of Guzzle's details.
         */
        return (string) $guzzleResponse->getBody();
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
    public function post(Array $postVars, Array $headers, $url=null)
    {
        try {
            $guzzleResponse = $this->_client->request(
                'POST',
                $url,
                array('multipart' => $postVars, 'headers' => $headers)
            );
        } catch (RequestException $e) {
            throw new GreenhouseAPIResponseException($e->getMessage());
        }
        
        return (string) $guzzleResponse->getBody();
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
        $guzzleParams = array();
        /**
         * There are three possibile inputs for $postParameter values.  Strings, Curlfiles, and arrays.
         * This look should process them in to something that Guzzle understands.
         */
        foreach ($postParameters as $key => $value) {
            if ($value instanceof \CURLFile) {
                $guzzleParams[] = array(
                    'name' => $key, 
                    'contents' => fopen($value->getFilename(), 'r'), 
                    'filename' => $value->getPostFilename()
                );
            } else if (is_array($value)) {
                foreach ($value as $val) $guzzleParams[] = array('name' => $key . '[]', 'contents' => "$val");
            } else {
                $guzzleParams[] = array('name' => $key, 'contents' => "$value");
            }
        }
        
        return $guzzleParams;
    }

    public function getClient()
    {
        return $this->_client;
    }
}