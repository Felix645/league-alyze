<?php


return [

    /**
     * Specifies the default timezone for all date and time related functions.
     *
     * Default: Europe/Berlin
     *
     * @var string
     */
    'timezone' => 'Europe/Berlin',

    /*
    / -----------------------------------------------------------------------------
    / Facade classes
    / -----------------------------------------------------------------------------
    /
    / Defines the accessors available to the frameworks
    / Artemis\Client\Facades\Facade class.
    / If you want to register a custom facade you can define its accessor here.
    /
    */

    'facades' => [
        'hash'          => \Artemis\Core\Hash\Hash::class,
        'view'          => \Artemis\Core\Template\View::class,
        'router'        => \Artemis\Core\Routing\Router::class,
        'settings'      => \Artemis\Core\Settings::class,
        'validation'    => \Artemis\Core\Validation\Validation::class,
        'curl'          => \Artemis\Utils\Curl::class,
        'mail'          => \Artemis\Core\Mail\Mailer::class,
        'upload'        => \Artemis\Core\FileHandling\Upload::class,
        'download'      => \Artemis\Core\FileHandling\Download::class,
        'redirector'    => \Artemis\Core\Http\Redirector::class,
        'api'           => \Artemis\Utils\API::class,
        'soap'          => \Artemis\Utils\Soap::class,
        'database'      => \Artemis\Core\Database\Database::class,
        'pagination'    => \Artemis\Core\Pagination\Paginator::class,
        'pipeline'      => \Artemis\Core\Pipeline\Pipeline::class,
        'gate_manager'  => \Artemis\Core\Auth\Access\GateManager::class,
    ],

    /*
    / -----------------------------------------------------------------------------
    / Container aliases
    / -----------------------------------------------------------------------------
    /
    / Defines container aliases for shorter container access.
    /
    */

    'aliases' => [
        'app'           => \Artemis\Application::class,
        'router'        => \Artemis\Core\Routing\Router::class,
        'request'       => \Artemis\Core\Http\Request::class,
        'response'      => \Artemis\Core\Http\Response::class,
        'settings'      => \Artemis\Core\Settings::class,
        'session'       => \Artemis\Core\Session::class,
        'template'      => \Artemis\Core\Template\Template::class,
        'auth_manager'  => \Artemis\Core\Auth\AuthManager::class,
        'redirect'      => \Artemis\Core\Http\Redirector::class,
        'date'          => \Artemis\Core\Date\Date::class,
        'view'          => \Artemis\Core\Template\View::class,
        'validation'    => \Artemis\Core\Validation\Validation::class,
        'api'           => \Artemis\Utils\API::class,
        'event'         => \Artemis\Core\Events\Dispatcher::class,
    ],

    /*
    / -----------------------------------------------------------------------------
    / Service Providers
    / -----------------------------------------------------------------------------
    /
    / Here are all Service Providers listed. You may add your own Providers here.
    /
    */

    'providers' => [
        // Framework Providers:
        \Artemis\Resource\Providers\AppServiceProvider::class,
        \Artemis\Resource\Providers\ValidationServiceProvider::class,
        \Artemis\Resource\Providers\AuthServiceProvider::class,
        \Artemis\Resource\Providers\MiddlewareServiceProvider::class,
        \Artemis\Resource\Providers\EventServiceProvider::class,
        \Artemis\Resource\Providers\RouteServiceProvider::class,
        // You may add your own Providers here:
        // Note that a ServiceProvider MUST implement \Artemis\Core\Interfaces\ProviderInterface
    ],
];