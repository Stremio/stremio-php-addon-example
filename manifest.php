<?php

	include 'helpers.php';

	// enable CORS and set JSON Content-Type

	setHeaders();

	// declare manifest

	$manifest = new stdClass();
	$manifest->id = "com.stremio.phpexample";
	$manifest->version = "1.0.0";
	$manifest->name = "PHP Add-on Example";
	$manifest->description = "Example Add-on made with PHP that server the Big Buck Bunny video.";
	$manifest->resources = array("catalog", "meta", "stream");
	$manifest->types = array("movie");


	// define catalog

	$catalog = new stdClass();
	$catalog->type = "movie";
	$catalog->id = "BigBuckBunnyCatalog";
	$catalog->name = "Big Buck Bunny Catalog";


	// set catalogs in manifest

	$manifest->catalogs = array($catalog);


	// print manifest in JSON format

	echo json_encode((array)$manifest);

?>