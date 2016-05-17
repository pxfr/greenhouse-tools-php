# Greenhouse Service Tools For PHP

This package of tools is provided by Greenhouse for customers who use PHP.  There are three tools provided.

1. **Job Board Service**: Used to embed iframes in your template or view files.  
2. **Job API Service**: Used to fetch data from the Greenhouse Job Board API.
3. **Application Service**: Used to send applications in to Greenhouse.

# Requirements
1. PHP Version 5.6 or greater. (Travis build passes on PHP 7.0; has not been manually tested).

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
$postParams = array('
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

# Exceptions
All exceptions raised by the Greenhouse Service library extend from `GreenhouseException`.  Catch this exception to catch anything thrown from this library.
