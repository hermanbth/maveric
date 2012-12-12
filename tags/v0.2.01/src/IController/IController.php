<?php
/**
* Interface for classes implementing a controller.
* Interfacet ska användas för alla kontrollers. Om en kontroller pekas ut utan en metod (t ex länken controller/)
* anropas metoden Index som kan köras i interfacet, det blir controller/index.
* 
* @package MavericCore
*/
interface IController {
  public function Index();
}
