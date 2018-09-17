<?php

	include 'helpers.php';

	// enable CORS and set JSON Content-Type

	setHeaders();

	// get request parameters

	$catalog = getRequestParams();

	// returns $catalog->type , $catalog->id and (if applicabale) $catalog->extra

	// we will not go into the details of using a database, instead we will serve JSON files located at: 
	// "./catalog/" . $catalog->type . "/" . $catalog->id . ".json"
	// ex: "./catalog/movie/BigBuckBunnyCatalog.json"

	$jsonPath = dirname(__FILE__) . '/catalog/' . $catalog->type . '/' . $catalog->id . '.json';

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