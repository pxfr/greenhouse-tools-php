<?php

namespace Greenhouse\GreenhouseJobBoardPhp\Clients;

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
    public function post();
}