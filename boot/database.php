<?php


/*
/ -----------------------------------------------------------------------------
/ Function: Database Configuration Check
/ -----------------------------------------------------------------------------
/
/ Defines a anonymous function that checks if given configuration
/ contains all necessary keys.
/ Throws an exception if one of the keys are missing.
/
*/


/**
 * Checks given database configuration.
 *
 * @param string $name
 * @param array $config
 *
 * @return void
 */
$checkDatabaseConfig = function($name, $config) {
    if( empty($config["host"])
        || empty($config["user"])
        || !isset($config["pass"])
        || empty($config["name"])
        || empty($config["char"]) ) {
        $message = "One or more keys are missing for database config '$name'";
        report(new \Artemis\Core\Database\Exceptions\ConfigException($message));
    }
};

/*
/ -----------------------------------------------------------------------------
/ Function: Add Database Connection
/ -----------------------------------------------------------------------------
/
/ Defines a anonymous function that adds database configuration to given
/ Eloquent Database Manager.
/
*/

/**
 * Adds given database connection to capsule.
 *
 * @param \Illuminate\Database\Capsule\Manager $capsule
 * @param string $name
 * @param array $config
 *
 * @return void
 */
$addConnection = function($capsule, $name, $config) {
    $capsule->addConnection([
        "driver" => $config["driver"] ?? "mysql",
        "host" => $config["host"],
        "database" => $config["name"],
        "username" => $config["user"],
        "password" => $config["pass"],
        "charset"   => $config["char"],
        "collation" => $config["coll"] ?? 'utf8_general_ci',
    ], $name);
};

/*
/ -----------------------------------------------------------------------------
/ Get database configuration
/ -----------------------------------------------------------------------------
/
/ Gets the database configurations from the frameworks
/ \Artemis\Resource\Configuration\Database class.
/
*/

$db_configs = require ROOT_PATH . 'config/database.php';

/*
/ -----------------------------------------------------------------------------
/ Eloquent Database Manager
/ -----------------------------------------------------------------------------
/
/ Initializes Eloquents Database Manager.
/
*/

$capsule = new \Illuminate\Database\Capsule\Manager();

/*
/ -----------------------------------------------------------------------------
/ Add connections
/ -----------------------------------------------------------------------------
/
/ Loops over the database configuration array and calls the anonymous
/ functions that were defined above.
/ Calls the frameworks ExceptionHandler if an Exception was thrown.
/
*/

if( !empty($db_configs) ) {
    foreach( $db_configs as $name => $db_config ) {
        if( !app()->databaseIsActive($name) ) {
            continue;
        }

        $checkDatabaseConfig($name, $db_config);
        $addConnection($capsule, $name, $db_config);
    }
}

/*
/ -----------------------------------------------------------------------------
/ Boot Eloquent
/ -----------------------------------------------------------------------------
/
/ Boots Eloquent
/
*/

$capsule->setAsGlobal();
$capsule->bootEloquent();

