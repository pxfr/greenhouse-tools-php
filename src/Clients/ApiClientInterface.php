<?php

namespace Greenhouse\GreenhouseToolsPhp\Clients;

/**
 * Interface wraps Guzzle so we can replace it someday (if necessary) without breaking
 * the rest of the package.
 */
interface ApiClientInterface
{
    /**
     * The expected behavior of this method is to get the response from the URL and return
     * the JSON response from the Greenhouse server. If the client returns something other 
     * than the response, the Greenhouse client should do the work to return the raw JSON.
     */
    public function get($url);
    
    /**
     * The expected behavior of this method is to take post parameters as formatted by 
     * $this->formatPostParameters and send it to the destination URL.  The second argument is an array of 
     * any additional headers that should be sent with the delivery.
     *
     * @params  Array   $postVars   An array of post parameters.
     * @params  Array   $headers    Additional headers.  Each item should be a full header string
     *                                  ex [0] => 'Basic <encoded_key>'
     * @params  string  $url        The $url should be set in the constructor but if it isn't, you can
     *                                  optionally include a POST url here.
     * @return  boolean
     * @throws  GreenhouseAPIResponseException  for non-200 responses
     */
    public function post(Array $postParams, Array $headers, $url);
    
    /**
     * This method should take the Greenhouse format of post parameters and transform
     * them to post parameters this client understands.  If both things are the same, 
     * Then this should just return $postParameters.  The format of Greenhouse Post
     * Parameters is defined in Services/ApplicationService.php
     *
     * @params  Array   $postParamters      Greenhouse-format post parameters
     * @return  mixed   Whatever method of sending POST that $this->post understands
     */
    public function formatPostParameters(Array $postParameters);
}