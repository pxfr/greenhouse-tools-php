<?php

namespace Greenhouse\GreenhouseToolsPhp\Tests\Services;

use Greenhouse\GreenhouseToolsPhp\Services\HarvestService;

/**
 * This test only tests that the service requests generate the expected links and arrays.  This does not
 * test the response from harvest and, in most cases, that the responses are valid.  Harvest is expected
 * to reject invalid requests.
 */
class HarvestServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->harvestService = new HarvestService('greenhouse');
        $apiStub = $this->getMockBuilder('\Greenhouse\GreenhouseToolsPhp\Client\GuzzleClient')
                        ->setMethods(array('send'))
                        ->getMock();
        $this->harvestService->setClient($apiStub);
        $this->expectedAuth = 'Basic Z3JlZW5ob3VzZTo=';
    }
    
    public function testGetActivityFeed()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'candidates/12345/activity_feed',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);
            
        $this->harvestService->getActivityFeedForCandidate($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetActivityFeedRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->getActivityFeedForCandidate($params);
    }
    
    public function testGetApplicationsNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'applications',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getApplications($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetApplicationsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'applications',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getApplications($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetApplication()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'applications/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getApplications($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPostAdvanceApplication()
    {
        $expected = array(
            'method' => 'post',
            'url' => 'applications/12345/advance',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"from_stage_id": 345}',
            'parameters' => array()
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"from_stage_id": 345}',
            'id' => 12345
        );

        $this->harvestService->postAdvanceApplication($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPostMoveApplication()
    {
        $expected = array(
            'method' => 'post',
            'url' => 'applications/12345/move',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"from_stage_id": 345}',
            'parameters' => array()
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"from_stage_id": 345}',
            'id' => 12345
        );

        $this->harvestService->postMoveApplication($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPostRejectApplication()
    {
        $expected = array(
            'method' => 'post',
            'url' => 'applications/12345/reject',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"from_stage_id": 345}',
            'parameters' => array()
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"from_stage_id": 345}',
            'id' => 12345
        );

        $this->harvestService->postRejectApplication($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testMoveApplicationRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->postMoveApplication($params);
    }
    
    public function testAdvanceApplicationRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->postAdvanceApplication($params);
    }
    
    public function testRejectApplicationRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->postRejectApplication($params);
    }

    public function testGetCandidatesNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'candidates',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getCandidates($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetCandidatesPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'candidates',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getCandidates($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetCandidate()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'candidates/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getCandidates($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPatchCandidate()
    {
        $expected = array(
            'method' => 'patch',
            'url' => 'candidates/12345',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'parameters' => array()
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'id' => 12345
        );

        $this->harvestService->patchCandidate($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPostAttachment()
    {
        $expected = array(
            'method' => 'post',
            'url' => 'candidates/12345/attachments',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'parameters' => array()
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'id' => 12345
        );

        $this->harvestService->postAttachmentForCandidate($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPutAnonymize()
    {
        $expected = array(
            'method' => 'put',
            'url' => 'candidates/12345/anonymize',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'parameters' => array('fields' => 'some,fields,go,here')
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'id' => 12345,
            'fields' => 'some,fields,go,here'
        );

        $this->harvestService->putAnonymizeCandidate($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testPostNote()
    {
        $expected = array(
            'method' => 'post',
            'url' => 'candidates/12345/activity_feed/notes',
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'parameters' => array()
        );
        $params = array(
            'headers' => array('On-Behalf-Of' => 234),
            'body' => '{"update_json": "is_here"}',
            'id' => 12345
        );

        $this->harvestService->postNoteForCandidate($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetDepartmentsNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'departments',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getDepartments($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetDepartmentsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'departments',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getDepartments($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetDepartment()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'departments/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getDepartments($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetEmailTemplatesNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'email_templates',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getEmailTemplates($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetEmailTemplatesPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'email_templates',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getEmailTemplates($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetEmailTemplate()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'email_templates/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getEmailTemplates($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }

    public function testGetJobPosts()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'job_posts',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();

        $this->harvestService->getJobPosts($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetJobPostsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'job_posts',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getJobPosts($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetJobPostsForJob()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'jobs/12345/job_post',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getJobPostsForJob($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetJobStagesForJob()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'jobs/12345/stages',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getJobStagesForJob($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }

    public function testGetJobsNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'jobs',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getJobs($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetJobsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'jobs',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getJobs($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetJob()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'jobs/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getJobs($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }

    public function testGetOfferNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'offers',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getOffers($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetOffersPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'offers',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getOffers($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetOffer()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'offers/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getOffers($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetOffersForApplications()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'applications/12345/offers',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getOffersForApplication($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetOffersForApplicationRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->getOffersForApplication($params);
    }
    
    public function testGetCurrentOfferForApplication()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'applications/12345/offers/current_offer',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getCurrentOfferForApplication($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetCurrentOfferForApplicationRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->getCurrentOfferForApplication($params);
    }
    
    public function testGetOfficesNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'offices',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getOffices($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetOfficesPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'offices',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getOffices($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetOffice()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'offices/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getOffices($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetRejectionReasonsNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'rejection_reasons',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getRejectionReasons($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetRejectionReasonsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'rejection_reasons',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getRejectionReasons($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetSecheduledInterviewsNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'scheduled_interviews',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getScheduledInterviews($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetScheduledInterviewsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'scheduled_interviews',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getScheduledInterviews($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetScorecardsNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'scorecards',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getScorecards($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetScorecardsPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'scorecards',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getScorecards($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetScorecard()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'scorecards/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getScorecards($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetScorecardForApplication()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'applications/12345/scorecards',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getScorecardsForApplication($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetScorecardForApplicationRequiresId()
    {
        $params = array('noid' => 12345);
        
        $this->expectException('\Greenhouse\GreenhouseToolsPhp\Services\Exceptions\GreenhouseServiceException');
        $this->harvestService->getScorecardsForApplication($params);
    }

    public function testGetSourcesNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'sources',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getSource($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetSourcesPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'sources',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getSource($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetSource()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'sources/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getSource($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetUsersNoPaging()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'users',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array();
            
        $this->harvestService->getUsers($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetUsersPaging()
    {
        $params = array('page' => 2, 'per_page' => 100);
        $expected = array(
            'method' => 'get',
            'url' => 'users',
            'headers' => array(),
            'body' => null,
            'parameters' => $params
        );
            
        $this->harvestService->getUsers($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
    
    public function testGetUser()
    {
        $expected = array(
            'method' => 'get',
            'url' => 'users/12345',
            'headers' => array(),
            'body' => null,
            'parameters' => array()
        );
        $params = array('id' => 12345);

        $this->harvestService->getUsers($params);
        $this->assertEquals($expected, $this->harvestService->getHarvest());
        $this->assertEquals($this->expectedAuth, $this->harvestService->getAuthorizationHeader());
    }
}