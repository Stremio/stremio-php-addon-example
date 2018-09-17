<?php

	include 'helpers.php';

	// get request parameters

	$meta = getRequestParams();

	// returns $meta->type , $meta->id and (if applicabale) $meta->extra

	// we will not go into the details of using a database, instead we will serve JSON files located at: 
	// "./meta/" . $meta->type . "/" . $meta->id . ".json"
	// ex: "./meta/movie/BigBuckBunny.json"

	$jsonPath = dirname(__FILE__) . '/meta/' . $meta->type . '/' . $meta->id . '.json';

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