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
* Set what to show as debug or developer information in the get_debug() theme helper.
* Skrivs ut i footern om det är påslaget här i filen.
*/
$maveric->config['debug']['maveric'] = false;
$maveric->config['debug']['session'] = false;
$maveric->config['debug']['timer'] = true;
//antalet databasfrågor
$maveric->config['debug']['db-num-queries'] = true;
//vad det var för databasfrågor
$maveric->config['debug']['db-queries'] = true;


/**
* Set database(s).
* Databasens DSN (Data Source Name) anges. DSN är ett allmänt sätt att ange en databasresurs som t ex kan vara en SQLite-databas.
*/
$maveric->config['database'][0]['dsn'] = 'sqlite:' . MAVERIC_SITE_PATH . '/data/.ht.sqlite';


/**
* What type of urls should be used?
*
* default      = 0      => index.php/controller/method/arg1/arg2/arg3
* clean        = 1      => controller/method/arg1/arg2/arg3
* querystring  = 2      => index.php?q=controller/method/arg1/arg2/arg3
*/
$maveric->config['url_type'] = 1;

/**
* Set a base_url to use another than the default calculated
* Oftast kommer inte utvecklaren att behöva använda detta men det kan hända.
*/
$maveric->config['base_url'] = null;

/*
* Define session name
*/
$maveric->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
// nyckeln till allt innehåll som finns lagrat i CMaveric
$maveric->config['session_key']  = 'maveric';

/*
* Define server timezone
*/
$maveric->config['timezone'] = 'Europe/Stockholm';

/*
* Define internal character encoding
*/
$maveric->config['character_encoding'] = 'UTF-8';

/*
* Define language
*/
$maveric->config['language'] = 'en';


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
$maveric->config['controllers'] = array(
								   'index' => array('enabled' => true,'class' => 'CCIndex'),
								   'developer' => array('enabled' => true,'class' => 'CCDeveloper'),
								   'guestbook' => array('enabled' => true,'class' => 'CCGuestbook'),
);

/**
* Settings for the theme.
* utvecklaren bestämmer vilka teman som ska vara aktiva, det går alltså att bestämma vilka teman som kan användas.
*/
$maveric->config['theme'] = array(
							 // The name of the theme in the theme directory
							 'name'    => 'core',
);