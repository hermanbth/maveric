<?php
/**
* Interface for classes implementing the singleton pattern.
* Alla metoder som anges här kommer alla klasser, som använder ISingleton (ex class CMaveric implements ISingelton), 
* att ha i den egna klassen.
* @package MavericCore
*/
interface ISingleton {
   public static function Instance();
}