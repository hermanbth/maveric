<?php
/**
* Bootstrapping, setting up and loading the core.
*
* @package MavericCore
*/

/**
* Enable auto-load of class declarations.
* När en klass skapas med new anropas denna funktion som laddar in klassfilen.
* Letar först efter filen i installations-katalogen och sedan i 
* site-katalogen, då kan utvecklaren ha egna klassfiler i site-katalogen. 
*
*/
function Autoload($aClassName) {
  $classFile = "/src/{$aClassName}/{$aClassName}.php";
   $file1 = MAVERIC_SITE_PATH . $classFile;
   $file2 = MAVERIC_INSTALL_PATH . $classFile;
   if(is_file($file1)) {
	  require_once($file1);
   } elseif(is_file($file2)) {
	  require_once($file2);
   }
}
spl_autoload_register('autoload');


/**
* Set a default exception handler and enable logging in it.
* Exceptions används som felhantering för att enkelt ta hand om fel som rör databasen. Detta är en standard-funktion som 
* samlar alla exceptions som inte hanteras. De exceptions kan loggas i en textfil.
*/
function exception_handler($exception) {
	echo "Maveric: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('exception_handler');


/**
 * Helper, include a file and store it in a string. Make $vars available to the included file.
 */
function getIncludeContents($filename, $vars=array()) {
  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}

/**
* Helper, wrap html_entites with correct character encoding
*/
function htmlent($str, $flags = ENT_COMPAT) {
	return htmlentities($str, $flags, CMaveric::Instance()->config['character_encoding']);
}