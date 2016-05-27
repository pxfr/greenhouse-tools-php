# Greenhouse Service Tools For PHP

This package of tools is provided by Greenhouse for customers who use PHP.  There are three tools provided.

1. **Job Board Service**: Used to embed iframes in your template or view files.  
2. **Job API Service**: Used to fetch data from the Greenhouse Job Board API.
3. **Application Service**: Used to send applications in to Greenhouse.

# Requirements
1. PHP Version 5.6 or greater. (Travis build passes on PHP 7.0; has not been manually tested).
2. [Composer](https://getcomposer.org/).  You should be using Composer to manage this package. 

# Installing
This is available on Packagist.  Install via Composer.  Add the following to your requirements:

```
    "grnhse/greenhouse-tools-php": "~1.0"
```


# Greenhouse Service
The Greenhouse Service is a parent service that returns the other Greenhouse Services.  By using this service, you have access to all the other services.  The Greenhouse service takes an array that optionally includes your job board URL Token [(found here in Greenhouse)](https://app.greenhouse.io/configure/dev_center/config/) and your Job Board API Credentials [(found here in Greenhouse)] (https://app.greenhouse.io/configure/dev_center/credentials).  Create a Greenhouse Service object like this:

```
<?php

use \Greenhouse\GreenhouseToolsPhp\GreenhouseService;
	
$greenhouseService = new GreenhouseService([
	'apiKey' => '<your_api_key>', 
	'boardToken' => '<your_board_token>'
]);

?>
```

Using this service, you can easily access the other Greenhouse Services and it will use the board token and client token as appropriate.

# The Job Board Service
This service generates the appropriate HTML tags for use with the Greenhouse iframe integration.  Use this service to generate either links to a Greenhouse-hosted job board or the appropriate tags for a Greenhouse iframe.  Access the job board service by calling:

```
<?php

$jobBoardService = $greenhouseService->getJobBoardService();

// Link to a Greenhouse hosted job board
$jobBoardService->linkToGreenhouseJobBoard();

// Link to a Greenhouse hosted job application
$jobBoardService->linkToGreenhouseJobApplication(12345, 'Apply to this job!', 'source_token');

// Embed a Greenhouse iframe in your page
$jobBoardService->embedGreenhouseJobBoard();

?>
```
# The Job API Service
Use this service to fetch public job board information from our job board API.  This services does not require an API key.  This is used to interact with the GET endpoints in the Greenhouse Job Board API.  These methods can be [found here](https://developers.greenhouse.io/job-board.html).  Access this service via:

```
$jobApiService = $greenhouseService->getJobApiService();
```

The methods in this service are named in relation to the endpoints, so to use the GET Offices endpoint, you'd call:

```
$jobApiService->getOffices();
```

And to get a specific office:

```
$jobApiService->getOffice($officeId);
```

The only additional parameters used in any case are for the "content" and "questions" parameters in the Jobs endpoint.  These are managed with boolean arguments that default to `false` in the `getJobs` and `getJob` methods.  To get all jobs with their content included, you'd call:

```
$jobApiService->getJobs(true);
```

while to get a job with its questions included, you'd call:

```
$jobApiService->getJob($jobId, true);
```
# The Application Service
Use this service to post Applications in to Greenhouse.  Use of this Service requires a Job Board API key which can be generated in Greenhouse.  Example usage of this service follows:

```
<?php

$appService = $greenhouseService->getApplicationApiService();
$postParams = array(
	'id' => 82354,
	'first_name' => 'Johnny',
	'last_name' => 'Test',
	'email' => 'jt@example.com',
	'resume' => new \CURLFile('path/to/file.pdf', 'application/pdf', 'resume.pdf'),
	'question_12345' => 'The answer you seek',
	'question_123456' => array(12345, 23456, 34567)
);
$appService->postApplication($postParams);

?>
```
The Application will handle generating an Authorization header based on your API key and posting the application as a multi-part form.  This parameter array follows the PHP convention except for the case of multiselect submission (submitting parameters with the same name).  While the PHP docs want users to submit multiple values like this:

```
'question_123456[0]' => 23456,
'question_123456[1]' => 12345,
'question_123456[2]' => 34567,
```

The Greenhouse packages requires you to do it like this:

```
'question_123456' => array(23456,12345,34567),
```

This prevents issues that arise for systems that do not understand the array-indexed nomenclature preferred by Libcurl.

# The Harvest Service
Use this service to interact with the Harvest API in Greenhouse.  Documentation for the Harvest API [can be found here.](https://developers.greenhouse.io/harvest.html/)  The purpose of this service is to make interactions with the Harvest API easier.  To create a Harvest Service object, you must supply an active Harvest API key.  Note that these are different than Job Board API keys.
```
<?php
$harvestService = $greenhouseService->getHarvestService();
?>
```

Via the Harvest service, you can interact with any Harvest methods outlined in the Greenhouse Harvest docs.  Harvest URLs fit mostly in to one of the following three formats:

1. `https://harvest.greenhouse.io/v1/<object>`: This is the most common URL format for GET methods in Greenhouse.  For endpoints in this format, the method will look like `$harvestService->getObject()`.  Examples of this are `$harvestService->getJobs()` or `$harvestService->getCandidates()`
2. `https://harvest.greenhouse.io/v1/<object>/<object_id>`: This will get the object with the given ID.  This is expected to only return or operate on one object.  The ID will always be supplied by an parameter array with a key named `id`.  For instance: `$harvestService->getCandidate($parameters);`
3. `https://harvest.greenhouse.io/v1/<object>/<object_id>/<sub_object>`: URLs in this format usually mean that you want to get all the sub_objects for the object with the object id.  Examples of this are `$harvestService->getJobStagesForJob(array('id' => 123))` and `$harvestService->getOffersForApplication(array('id' => 123))`
4. Some method calls and URLs do not exactly fit this format, but the methods were named as close to fitting that format as possible.  These include:
  * `getActivityFeedForCandidate`: [Get a candidate's activity feed](https://developers.greenhouse.io/harvest.html#retrieve-activity-feed-for-candidate)
  * `postNoteForCandidate`: [Add a note to a candidate](https://developers.greenhouse.io/harvest.html#create-a-candidate-39-s-note)
  * `putAnonymizeCandidate`: [Anonymize some fields on a candidate](https://developers.greenhouse.io/harvest.html#anonymize-a-candidate)
  * `getCurrentOfferForApplication`: [Get only the current offer for a candidate](https://developers.greenhouse.io/harvest.html#retrieve-current-offer-for-application)
  * `postAdvanceApplication`: [Advance an application to the next stage](https://developers.greenhouse.io/harvest.html#advance-an-application)
  * `postMoveApplication`: [Move an application to any stage.](https://developers.greenhouse.io/harvest.html#move-an-application)
  * `postRejectApplication`: [Reject an application](https://developers.greenhouse.io/harvest.html#reject-an-application)

You should use the parameters array to supply any URL parameters and headers required by the harvest methods.  For any items that require a JSON body, this will also be supplied in the parameter array.  


Ex: [Moving an application](https://developers.greenhouse.io/harvest.html#move-an-application)
```
$parameters = array(
    'id' => $applicationId,
    'headers' => array('On-Behalf-Of' => $auditUserId),
    'body' => '{"from_stage_id": 123, "to_stage_id": 234}'
);
$harvestService->moveApplication($parameters);
```

Note you do not have to supply the authorization header in the `headers` array.  This will be appended to the headers array automatically presuming the supplied API key is valid.

The parameters array is also used to supply any paging and filtering options that would normally be supplied as a GET query string.  Anything that is not in the `id`, `headers`, or `body` key will be assumed to be a URL parameter.  

Ex: [Getting a page of applications](https://developers.greenhouse.io/harvest.html#list-applications)
```
$parameters = array(
    'per_page' => 100,
    'page' => 2
);
$harvestService->getApplications($parameters);
```

If the ID key is supplied in any way, that will take precedence.

**A note on future development**: The Harvest package makes uses PHP's magic `__call` method.  This was to handle the case where Greenhouse's Harvest API advances past this package.  New endpoint URLs should automatically work if they're added in the same format.  If Greenhouse adds a GET `https://harvest.greenhouse.io/v1/widgets` endpoint, calling `$harvestService->getWidgets()` should be automatically supported by this package.


# Exceptions
All exceptions raised by the Greenhouse Service library extend from `GreenhouseException`.  Catch this exception to catch anything thrown from this library.
