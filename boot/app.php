<?php

/*
/ -----------------------------------------------------------------------------
/ Get application
/ -----------------------------------------------------------------------------
/
/ As the application implements the singleton pattern it gets
/ initialized by its getInstance() method.
/
*/

$app = new \Artemis\Application();

/*
/ -----------------------------------------------------------------------------
/ Load .env
/ -----------------------------------------------------------------------------
/
/ Gets the configured .env-path and hands to the application to load it.
/
*/

$env = require_once ROOT_PATH . 'boot/env.php';
$app->loadEnv($env);

/*
/ -----------------------------------------------------------------------------
/ Configuration
/ -----------------------------------------------------------------------------
/
/ Get configuration array needed for the booting process.
/
*/

$config = require_once ROOT_PATH . 'config/app.php';

/*
/ -----------------------------------------------------------------------------
/ Timezone
/ -----------------------------------------------------------------------------
/
/ Sets the default time zone defined in config/app.php
/
*/

date_default_timezone_set($config['timezone']);


/*
/ -----------------------------------------------------------------------------
/ Boot Aliases
/ -----------------------------------------------------------------------------
/
/ Boots container class aliases.
/
*/

$app->bootContainerAliases($config['aliases']);


/*
/ -----------------------------------------------------------------------------
/ Boot Facades
/ -----------------------------------------------------------------------------
/
/ Boots the Facades returned from the configuration.
/
*/

$app->bootFacades($config['facades']);

/*
/ -----------------------------------------------------------------------------
/ Binding core classes
/ -----------------------------------------------------------------------------
/
/ Binding core classes as singletons to the container.
/
*/

$container = container();

$container->instance(\Artemis\Application::class, $app);

$container->singleton(\Artemis\Core\Auth\AuthManager::class, function() {
    return new \Artemis\Core\Auth\AuthManager();
});

$container->singleton(\Artemis\Core\Database\DBManager::class, function() {
    return new \Artemis\Core\Database\DBManager();
});

$container->singleton(\Artemis\Core\Events\Dispatcher::class, function() {
    return new \Artemis\Core\Events\Dispatcher();
});

$container->singleton(\Artemis\Core\Http\Request::class, function() {
    return new \Artemis\Core\Http\Request();
});

$container->singleton(\Artemis\Core\Http\Response::class, function() {
    return new \Artemis\Core\Http\Response();
});

$container->singleton(\Artemis\Core\Session::class, function() {
    return new \Artemis\Core\Session();
});

$container->singleton(\Artemis\Core\Settings::class, function() {
    return new \Artemis\Core\Settings();
});

$container->singleton(\Artemis\Core\Middleware\MiddlewareStack::class, function() {
    return new \Artemis\Core\Middleware\MiddlewareStack();
});

$container->singleton(\Artemis\Core\Routing\Router::class, function() {
    return new \Artemis\Core\Routing\Router();
});

$container->singleton(\Artemis\Core\Template\Template::class, function() {
    return new \Artemis\Core\Template\Template();
});

$container->singleton(\Artemis\Core\Auth\UserRepository::class, function() {
    return new \Artemis\Core\Auth\UserRepository();
});

$container->singleton(\Artemis\Core\Template\View::class, function() {
    return new \Artemis\Core\Template\View();
});

$container->singleton(\Artemis\Core\Validation\Validation::class, function() {
    return new Artemis\Core\Validation\Validation();
});

$container->singleton(\Artemis\Resource\Providers\ExceptionServiceProvider::class, function() {
    return new \Artemis\Resource\Providers\ExceptionServiceProvider();
});

$container->singleton(\Artemis\Core\Auth\Access\GateManager::class, function() {
    return new \Artemis\Core\Auth\Access\GateManager();
});

$container->bind(\Artemis\Core\Cache\CachingInterface::class, \Artemis\Core\Cache\FileCache::class);

/*
/ -----------------------------------------------------------------------------
/ Start Request
/ -----------------------------------------------------------------------------
/
/ Kickstarts the request building process.
/
*/

if( !$app->fromCLI() ) {
    $app->request()->start();
}

/*
/ -----------------------------------------------------------------------------
/ Register handlers
/ -----------------------------------------------------------------------------
/
/ Registers the default error and exception handlers.
/
*/

/** @var \Artemis\Resource\Providers\ExceptionServiceProvider $exceptionHandler */
$exceptionHandler = $container->get(\Artemis\Resource\Providers\ExceptionServiceProvider::class);

$exceptionHandler->registerErrorHandler();
$exceptionHandler->registerExceptionHandler();
$exceptionHandler->register();

/*
/ -----------------------------------------------------------------------------
/ Boot .env
/ -----------------------------------------------------------------------------
/
/ Boots some .env Configurations into the Application instance.
/
*/

$env_config = require_once ROOT_PATH . 'config/env.php';

$app->bootEnv($env_config);


$exceptionHandler->recheckDebugMode();

/*
/ -----------------------------------------------------------------------------
/ Load Eloquent connections
/ -----------------------------------------------------------------------------
/
/ As Laravel's Eloquent ORM is a dependency of this framework,
/ it needs information about the database connections.
/ The script below will initialize Eloquent's database manager.
/
*/

require_once ROOT_PATH . 'boot/database.php';

/*
/ -----------------------------------------------------------------------------
/ Boot API configuration.
/ -----------------------------------------------------------------------------
/
/ Boots the API configurations from the corresponding config file.
/
*/

$api_config = require ROOT_PATH . 'config/api.php';
$app->loadApiConfig($api_config);



/*
/ -----------------------------------------------------------------------------
/ Boot Settings
/ -----------------------------------------------------------------------------
/
/ Boots database settings table if any is defined.
/
*/

$app->bootSettings();

/*
/ -----------------------------------------------------------------------------
/ Boot Service Providers
/ -----------------------------------------------------------------------------
/
/ Boots the Service Providers defined in config/app.php.
/
*/

$app->bootServiceProviders($config['providers']);

/*
/ -----------------------------------------------------------------------------
/ Pagination config
/ -----------------------------------------------------------------------------
/
/ Sets Laravel's pagination configuration.
/
*/

require_once ROOT_PATH . 'boot/pagination.php';

/*
/ -----------------------------------------------------------------------------
/ Return Application
/ -----------------------------------------------------------------------------
/
/ Returns the configured Application instance to the index.php
/
*/

return $app;