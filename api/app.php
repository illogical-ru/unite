<?php

// --- autoloader ----------------------------------------------------------- //

$loader = require 'vendor/autoload.php';

$loader->addPsr4('',             __DIR__ . '/lib');
$loader->addPsr4('Model\\',      __DIR__ . '/models');
$loader->addPsr4('Controller\\', __DIR__ . '/controllers');

// --- conf ----------------------------------------------------------------- //

$conf   = App::conf(require 'conf.php');

if ($conf->charset && function_exists('mb_internal_encoding')) {
    mb_internal_encoding     ($conf->charset);
}
if ($conf->timezone) {
    date_default_timezone_set($conf->timezone);
}

// -------------------------------------------------------------------------- //

?>
