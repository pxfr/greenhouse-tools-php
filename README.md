# Greenhouse Service Tools For PHP

This package of tools is provided by Greenhouse for customers who use PHP.  There are three tools provided.

1. **Job Board Service**: Used to embed iframes in your template or view files.  
2. **Job API Service**: Used to fetch data from the Greenhouse Job Board API.
3. **Application Service**: Used to send applications in to Greenhouse.

# Greenhouse Service
The Greenhouse Service is a parent service that returns the other Greenhouse Services.  By using this service, you have access to all the other services.  The Greenhouse service takes an array that optionally includes your job board URL Token [https://app.greenhouse.io/configure/dev_center/config/](found here in Greenhouse) and your Job Board API Credentials [https://app.greenhouse.io/configure/dev_center/credentials](found here in Greenhouse).  Create a Greenhouse Service object like this:

```
<?php
	use \Greenhouse\GreenhouseToolsPhp\GreenhouseService;
	
	$service = new GreenhouseService([
		'apiKey' => '<your_api_key>', 
		'boardToken' => '<your_board_token>'
	]);
?>
```

Using this service, you can easily access the other Greenhouse Services and it will use the board token and client token as appropriate.

# The Job Board Service

# The Job API Service

# The Application Service

