<?php
/**
* Site configuration, this file is changed by user per site.
* Här läggs de inställnigar som är specifika för utvecklarens webbplats.
*
*/

/*
* Set level of error reporting
*/
error_reporting(-1);
ini_set('display_errors', 1);

/**
* What type of urls should be used?
*
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$maveric->SetConfig('url_type', 1);

/**
* Set a base_url to use another than the default calculated
* Oftast kommer inte utvecklaren att behöva använda detta men det kan hända.
*/
$maveric->SetConfig('base_url', null);

/*
* Define session name
*/
$maveric->SetConfig('session_name', preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]));

/*
* Define server timezone
*/
$maveric->SetConfig('timezone', 'Europe/Stockholm');

/*
* Define internal character encoding
*/
$maveric->SetConfig('character_encoding', 'UTF-8');

/*
* Define language
*/
$maveric->SetConfig('language', 'en');

/**
* Define the controllers, their classname and enable/disable them.
* Utvecklaren bestämmer vilka kontroller som ska vara aktiva och vilken klassfil som motsvarar en kontroller.
*
* The array-key is matched against the url, for example:
* the url 'developer/dump' would instantiate the controller with the key "developer", that is
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $maveric->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/
// på nyckeln controllers i config-medlemen läggs en array som värde. Den arrayen innehåller nyckeln index som i sin tur har en array med nyckeln
// enabled som bestämmer om index-kontrollern är aktiv och vilken klassfil som motsvara denna kontroller. 
$maveric->SetConfig('controllers', array(
								   'index' => array('enabled' => true,'class' => 'CCIndex'),
								   'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
));

/**
* Settings for the theme.
* utvecklaren bestämmer vilka teman som ska vara aktiva, det går alltså att bestämma vilka teman som kan användas.
*/
$maveric->SetConfig('theme', array(
							 // The name of the theme in the theme directory
							 'name'    => 'core',
));