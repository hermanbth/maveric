<?php
/**
* Standard controller layout.
*
* @package MavericCore
*/
class CCIndex implements IController {

   /**
	* Implementing interface IController. All controllers must have an index action.
	*/
   public function Index() {   
	  global $maveric;
	  $maveric->SetData('title', "The Index Controller");
	  $maveric->SetData('main', "<h1>The Index Controller</h1>");
   }

} // avslutar klassen