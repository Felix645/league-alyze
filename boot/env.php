<?php

use Artemis\Support\FileSystem;

/*
/ -----------------------------------------------------------------------------
/ .env Path
/ -----------------------------------------------------------------------------
/
/ Defines the .env-path with the frameworks ROOT_PATH constant.
/
*/

$env_path = ROOT_PATH . ".env";

/*
/ -----------------------------------------------------------------------------
/ Check for existence
/ -----------------------------------------------------------------------------
/
/ As some parts of the framework are dependent on the .env file,
/ the script execution will die with a message if no .env file was found.
/
*/

if( !FileSystem::exists($env_path) ) {
    die("No '.env' file found! Please provide a '.env' file in your projects root directory!");
}

/*
/ -----------------------------------------------------------------------------
/ Return .env-path
/ -----------------------------------------------------------------------------
/
/ Returns the generated .env-path
/
*/

return $env_path;