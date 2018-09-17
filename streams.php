<?php

	include 'helpers.php';

	// get request parameters
	$streams = getRequestParams();

	// returns $streams->type , $streams->id and (if applicabale) $streams->extra

	// we will not go into the details of using a database, instead we will serve JSON files located at: 
	// "./streams/" . $streams->type . "/" . $streams->id . ".json"
	// ex: "./streams/movie/BigBuckBunny.json"

	$jsonPath = dirname(__FILE__) . '/stream/' . $streams->type . '/' . $streams->id . '.json';

	if (realpath($jsonPath)) {

		// file exists

		// enable CORS and set JSON Content-Type
		setHeaders();

		// respond with json file from file system
		echo file_get_contents($jsonPath);

	} else {

		// respond with 404 page
		page404();

	}

?>