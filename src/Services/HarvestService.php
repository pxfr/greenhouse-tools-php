<?php

namespace Greenhouse\GreenhouseToolsPhp\Services;

use Greenhouse\GreenhouseToolsPhp\Services\ApiService;
use Greenhouse\GreenhouseToolsPhp\Clients\GuzzleClient;
use Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException;
use Greenhouse\GreenhouseToolsPhp\Tools\HarvestHelper;

/**
 * This class interacts with Greenhouse's Harvest API.
 */
class HarvestService extends ApiService
{
    private $_harvestHelper;
    private $_harvest;
    
    public function __construct($apiKey)
    {
        $this->_apiKey = $apiKey;
        $client = new GuzzleClient(array('base_uri' => self::HARVEST_V1_URL));
        $this->setClient($client);
        $this->_authorizationHeader = $this->getAuthorizationHeader($apiKey);
        $this->_harvestHelper = new HarvestHelper();
    }
    
    public function getHarvest()
    {
        return $this->_harvest;
    }

    public function sendRequest()
    {
        $authHeader = array('Authorization' => $this->_authorizationHeader);
        $allHeaders = array_merge($this->_harvest['headers'], $authHeader);
        $requestUrl = $this->_harvestHelper->addQueryString($this->_harvest['url'], $this->_harvest['parameters']);
        $options = array(
            'headers' => $allHeaders,
            'body' => $this->_harvest['body'],
        );

        return $this->_apiClient->send($this->_harvest['method'], $requestUrl, $options);
    }
    
    /**
     * In order to keep up to date with changes to the Harvest api and not trigger a re-release of this 
     * package each time a new method is created, the magic Call method is used to construct URLs to the
     * Harvest API.  This will use the called method and the arguments provided to create the proper URL
     * to request the service.  This should return the response from the API on success and raise an 
     * exception on failure.  In most cases, this should be straightforward parsing.
     *
     * 1) getApplications() will transform in to a Get request to "applications"
     * 2) getApplications(array('id' => 12345)) will translate to a GET request to "applications/12345"
     * 3) getScorecardsForApplications(array('id' => 12345)) will translate to 
     *      "applications/12345/scorecards
     */
    public function __call($name, $arguments)
    {
        $args = sizeof($arguments) > 0 ? $arguments[0] : array();
        $this->_harvest = $this->_harvestHelper->parse($name, $args);
        return $this->sendRequest();
    }
    
    /**
     * All methods below this point are methods that don't fit in the standard url format.  Either the 
     * words are not pluralized (application/12345/move instead of moves) or there is an additional word
     * at the end of the URL (application/123/offers/current_offer) which we can't handle in the magic method
     * above.  Standard URLs that fit the common Harvest format will work automatically going forward.  Any
     * exceptions should go below this line.
     */
    
    public function getActivityFeedForCandidate($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('getActivityFeedForCandidate', $parameters);
        return $this->_trimUrlAndSendRequest();
    }

    public function postNoteForCandidate($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('postActivityFeedForCandidate', $parameters);
        $this->_harvest['url'] = 'candidates/' . $parameters['id'] . '/activity_feed/notes';
        $this->sendRequest();
    }
    
    public function putAnonymizeCandidate($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('putAnonymizeForCandidate', $parameters);
        return $this->_trimUrlAndSendRequest();
    }

    public function getJobPostsForJob($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('getJobPostForJob', $parameters);
        return $this->_trimUrlAndSendRequest();
    }
    
    public function getJobStagesForJob($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('getStagesForJob', $parameters);
        return $this->sendRequest();
    }
    
    public function getCurrentOfferForApplication($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('getOffersForApplication', $parameters);
        $this->_harvest['url'] = $this->_harvest['url'] . '/current_offer';
        return $this->sendRequest();
    }
    
    public function postAdvanceApplication($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('postAdvanceForApplication', $parameters);
        return $this->_trimUrlAndSendRequest();
    }
        
    public function postMoveApplication($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('postMoveForApplication', $parameters);
        return $this->_trimUrlAndSendRequest();
    }

    public function postRejectApplication($parameters=array())
    {
        $this->_harvest = $this->_harvestHelper->parse('postRejectForApplication', $parameters);
        return $this->_trimUrlAndSendRequest();
    }

    private function _trimUrlAndSendRequest()
    {
        $this->_harvest['url'] = substr($this->_harvest['url'], 0, -1);
        return $this->sendRequest();
    }
}