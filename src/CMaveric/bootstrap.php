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