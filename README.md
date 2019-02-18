# Stremio PHP Add-on Example

This example add-on uses files from [Stremio Static Add-on Example](https://github.com/Stremio/stremio-static-addon-example)

**Important: Remote Stremio Add-ons only work through HTTPS**


What this example covers:

- Configuring Apache Web Server's `.htaccess` to route the HTTP(s) requests that are expected from Stremio Add-ons to our PHP files
- Declaring the Add-on Manifest with PHP's `stdClass` and converting it to a JSON string in order to reply with it when requested
- Reading from static JSON files and replying with their contents on request
- Setting correct Headers for responses (`CORS`, `Content-Type`)
- Getting request parameters from requests


What this example does not cover:

- Getting information from a DB in order to reply to requests
- Searching for items in catalogs
- Responding to subtitle requests


## Configuring Apache Web Server's `.htaccess`

**.htaccess** file contents:

```
RewriteEngine On
RewriteBase /

RewriteRule ^manifest.json manifest.php

RewriteRule ^catalog/(.*)/(.*)/(.*).json catalogs.php?type=$1&id=$2&extra=$3 [B]
RewriteRule ^meta/(.*)/(.*)/(.*).json meta.php?type=$1&id=$2&extra=$3 [B]
RewriteRule ^stream/(.*)/(.*)/(.*).json streams.php?type=$1&id=$2&extra=$3 [B]

RewriteRule ^catalog/(.*)/(.*).json catalogs.php?type=$1&id=$2
RewriteRule ^meta/(.*)/(.*).json meta.php?type=$1&id=$2
RewriteRule ^stream/(.*)/(.*).json streams.php?type=$1&id=$2
```

This will route all required requests for `catalog`, `meta` and `stream` to the PHP files that will handle the requests.

Note:

- you can also add `subtitle` requests in the same pattern as the others, this is not part of `.htaccess` as this resource is not handled in this example
- if you want to add this example in a sub-folder on your server, it will need to be specified in the `RewriteBase`, for example, if this add-on is uploaded to `/stremio-php-addon-example/` on your server, then set `RewriteBase /stremio-php-addon-example/` instead of `RewriteBase /` in your `.htaccess` file


## Declaring the Add-on Manifest

**manifest.php** file contents:

```php
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
```

This will create an `stdClass` (PHP Object Class) with the required `manifest.json` parameters and respond with a JSON string based on the PHP object.

For the complete list of parameters that can be set in `manifest.json`, please see [this page](https://github.com/Stremio/stremio-addon-sdk/tree/master/docs/api/responses/manifest.md).


## Reading from static JSON files

**catalogs.php** file contents:

```php
<?php
include 'helpers.php';

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
```

`meta.php`, `catalogs.php` and `streams.php` all work with this same pattern, they simply read from a JSON file on disk and reply with it's contents in case it exists, otherwise it shows a 404 page.

This add-on example does not go in the details of using a database, although, based on `$catalog->type` and `$catalog->id` a database query could be easily added to this example.

This add-on also does not show an example of handling search requests, which would normally be implemented in `catalogs.php` by checking for the existence of a search query in `$catalog->extra->search`. Searching would also require a change in the manifest, more specifically setting `$catalog->extraSupported = array('search')` to the catalog definition.

All requests expect a response in the JSON string format.


## Testing the add-on

Simply upload this example to an Apache Web Server with PHP support. If you add this example to a sub-folder instead of the main web server folder, make sure to make the required `.htaccess` changes as mentioned above.

Then open Stremio, go to the add-ons page (puzzle icon button in the top right), and write your `manifest.json` URL in the top left side input field (where it writes "Add-On Repository Url").

Your `manifest.json` URL should be relative to the directory where you uploaded the example on your web server, for example it would be `https://www.mydomain.com/manifest.json` if you uploaded to your primary folder, or `https://www.mydomain.com/stremio-php-addon-example/manifest.json` if you uploaded to the `/stremio-php-addon-example/` folder.


### Don't know where to add the Add-on Repository URL?

![add-on-repository-url](https://user-images.githubusercontent.com/1777923/43146711-65a33ccc-8f6a-11e8-978e-4c69640e63e3.png)
