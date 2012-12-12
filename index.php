<?php
/**
 * All requests routed through here. This is an overview of what actaully happens during
 * a request.
 *
 * @package MavericCore
 */

// ---------------------------------------------------------------------------------------
//
// PHASE: BOOTSTRAP
//
define('MAVERIC_INSTALL_PATH', dirname(__FILE__));
define('MAVERIC_SITE_PATH', MAVERIC_INSTALL_PATH . '/site');

require(MAVERIC_INSTALL_PATH.'/src/bootstrap.php');

$ma = CMaveric::Instance();


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
$ma->FrontControllerRoute();


// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
$ma->ThemeEngineRender();
