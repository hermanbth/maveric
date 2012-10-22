<?php
/**
* Helpers for theming, available for all themes in their template files and functions.php.
* This file is included right before the themes own functions.php
*/

/**
* Create a url by prepending the base_url.
*/
function createBaseUrlByUrl($url) {
	return CMaveric::Instance()->request->GetBaseUrl() . trim($url, '/');
}

/**
* Return the current url.
*/
function GetCurrentUrlFromRequest() {
	return CMaveric::Instance()->request->GetCurrentUrl();
}