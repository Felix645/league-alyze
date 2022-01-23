<?php


use Artemis\Core\Console\Make as Make;
use Artemis\Core\Console\DB as DB;
use Artemis\Core\Console\Cache as Cache;
use \Artemis\Core\Console\Maintenance as Maintenance;


/*
/ -----------------------------------------------------------------------------
/ Include framework helper functions
/ -----------------------------------------------------------------------------
/
/ Initializes the frameworks helper functions.
/ These are used by the framework for convenience and thus are required.
/
*/

require_once ROOT_PATH . 'artemis/Core/_helpers.php';

/*
/ -----------------------------------------------------------------------------
/ Require Auto Loader
/ -----------------------------------------------------------------------------
/
/ This is absolutely required for the framework to work.
/ Composer provides easy autoloading and external dependencies.
/
*/

require ROOT_PATH . 'vendor/autoload.php';

/*
/ -----------------------------------------------------------------------------
/ Run application
/ -----------------------------------------------------------------------------
/
/ Gets the configured application instance and then runs it.
/
*/

$app = require_once ROOT_PATH . 'boot/app.php';

/*
/ -----------------------------------------------------------------------------
/ Kronos Commands
/ -----------------------------------------------------------------------------
/
/ Collection of all kronos command.
/ Custom Commands my be added under the 'custom' section below.
/
*/

return [
    'commands' => [
        'default' => [
            new \Artemis\Core\Console\Version(),
            new Make\Controller(),
            new Make\Request(),
            new Make\Middleware(),
            new Make\Mail(),
            new Make\Model(),
            new Make\Seeder(),
            new Make\Migration(),
            new Make\Event(),
            new Make\Listener(),
            new DB\Seed(),
            new DB\Migrate(),
            new DB\User(),
            new DB\Ldap(),
            new DB\Settings(),
            new DB\Api(),
            new DB\Framework(),
            new Cache\Clear(),
            new Maintenance\Down(),
            new Maintenance\Up()
        ],
        'custom' => [
            new \App\Commands\Backup(),
        ],
    ]
];