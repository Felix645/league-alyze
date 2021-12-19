<?php

/*
/ -----------------------------------------------------------------------------
/ Define application directory constants used by the framework
/ -----------------------------------------------------------------------------
/
/ Because those paths are set in composer.json for auto-loading
/ they may not be changed!
/
*/

if( !defined( __DIR__ ) ) define( __DIR__, dirname(__FILE__) );

const ROOT_PATH = __DIR__ . '/';
const CONTENT_PATH = ROOT_PATH . 'app/';

/*
/ -----------------------------------------------------------------------------
/ Include framework helper functions
/ -----------------------------------------------------------------------------
/
/ Initializes the frameworks helper functions.
/ These are used by the framework for convenience and thus are required.
/
*/

require_once "artemis/Core/_helpers.php";

/*
/ -----------------------------------------------------------------------------
/ Require Auto Loader
/ -----------------------------------------------------------------------------
/
/ This is absolutely required for the framework to work.
/ Composer provides easy autoloading and external dependencies.
/
*/

require "vendor/autoload.php";

/*
/ -----------------------------------------------------------------------------
/ Run application
/ -----------------------------------------------------------------------------
/
/ Gets the configured application instance and then runs it.
/
*/

$app = require_once 'boot/app.php';
$app->run();