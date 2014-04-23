<?php
//
// PHASE: BOOTSTRAP
//
define('ROOT_PATH', __DIR__);
define('PRETTO_INSTALL_PATH', dirname(__FILE__));
define('PRETTO_SITE_PATH', PRETTO_INSTALL_PATH . '/site');

require(PRETTO_INSTALL_PATH.'/src/CPretto/bootstrap.php');

$pr = CPretto::instance();

//
// PHASE: FRONTCONTROLLER ROUTE
//
$pr->FrontControllerRoute();


//
// PHASE: THEME ENGINE RENDER
//
$pr->ThemeEngineRender();