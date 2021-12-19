<?php


namespace Artemis;


use Artemis\Client\Facades\Schema;
use Artemis\Core\Exception\NotFoundException;
use Artemis\Core\Http\ResponseHandler;
use Artemis\Core\Interfaces\ProviderInterface;
use Artemis\Core\Middleware\MiddlewareStack;
use Artemis\Core\Providers\AuthServiceProvider;
use Artemis\Core\Providers\MiddlewareServiceProvider;
use Artemis\Core\Routing\Exceptions\RouteException;
use Symfony\Component\Dotenv\Dotenv;
use Artemis\Core\DI\Container;


class Application
{
    /**
     * Framework Version of the current build.
     *
     * @var string
     */
    private const FRAMEWORK_VERSION = 'v1.2.0';

    /**
     * Application Domain URL.
     *
     * @var string
     */
    private $app_domain;

    /**
     * Application relative path.
     *
     * @var string
     */
    private $app_path;

    /**
     * Applications debug mode.
     *
     * @var bool
     */
    private $debug_mode;

    /**
     * Application asset path.
     *
     * @var string
     */
    private $assets;

    /**
     * API configuration array.
     *
     * @var array
     */
    private $api_config;

    /**
     * Returns the app root path.
     * Note: The string returns with a '/'.
     *
     * @return string
     */
    public function root()
    {
        return ROOT_PATH;
    }

    /**
     * Checks if the given database key is active.
     *
     * @param string $db_key
     *
     * @return bool
     */
    public function databaseIsActive($db_key)
    {
        $inactive_databases = explode(',', env('INACTIVE_DBS', ''));

        return !in_array($db_key, $inactive_databases);
    }

    /**
     * Gets the app domain.
     *
     * @return string
     */
    public function domain()
    {
        return $this->app_domain;
    }

    /**
     * Gets the app path.
     *
     * @return string
     */
    public function path()
    {
        return $this->app_path;
    }

    /**
     * Gets the app debug mode.
     *
     * @return bool
     */
    public function debug()
    {
        return $this->debug_mode ?? true;
    }

    /**
     * Gets the app asset path.
     *
     * @return string
     */
    public function assets()
    {
        return $this->assets;
    }

    /**
     * Gets the api config array.
     *
     * @return array
     */
    public function api_config()
    {
        return $this->api_config;
    }

    /**
     * Gets the current framework version.
     *
     * @return string
     */
    public function version() : string
    {
        return self::FRAMEWORK_VERSION;
    }

    /**
     * Checks if the application was called from CLI.
     *
     * @return bool
     */
    public function fromCLI() : bool
    {
        return 'cli' === php_sapi_name();
    }

    /**
     * Loads given env
     *
     * @param string $env_path
     *
     * @return void
     */
    public function loadEnv($env_path)
    {
        $dotenv = new Dotenv();
        $dotenv->load($env_path);
    }

    /**
     * Loads given api config array.
     *
     * @param array $config
     *
     * @return void
     */
    public function loadApiConfig($config)
    {
        $this->api_config = $config;
    }

    /**
     * Boots .env configs.
     *
     * @param $configs
     *
     * @return void
     */
    public function bootEnv($configs)
    {
        $this->app_domain = $configs['domain'];
        $this->app_path = $configs['path'];
        $this->debug_mode = boolval($configs['debug']);
        $this->assets =$configs['assets'];
    }

    /**
     * Boots given facade accessors
     *
     * @param array $facades
     */
    public function bootFacades($facades)
    {
        foreach( $facades as $accessor => $class ) {
            \Artemis\Client\Facades\Facade::addAccessor($accessor, $class);
        }
    }

    /**
     * Boots given container aliases.
     *
     * @param array $aliases
     *
     * @return void
     */
    public function bootContainerAliases($aliases)
    {
        foreach( $aliases as $alias => $class ) {
            $this->container()->addAlias($alias, $class);
        }
    }

    /**
     * Boots settings if any are defined.
     *
     * @return void
     */
    public function bootSettings()
    {
        $this->storeSettings();
    }

    /**
     * Boots ServiceProviders.
     *
     * @param array $providers
     *
     * @return void
     */
    public function bootServiceProviders($providers)
    {
        /* @var ProviderInterface[] $provider_instances */
        $provider_instances = [];

        foreach( $providers as $provider_class ) {
            if( !is_subclass_of($provider_class, ProviderInterface::class) ) {
                continue;
            }

            if( $this->fromCLI() ) {
                if( $provider_class instanceof AuthServiceProvider || $provider_class instanceof MiddlewareServiceProvider ) {
                    continue;
                }
            }

            $provider_instances[] = $this->container()->get($provider_class);
        }

        foreach( $provider_instances as $provider ) {
            $provider->register();
        }

        foreach( $provider_instances as $provider ) {
            $provider->boot();
        }
    }

    /**
     * Gets the DI container
     *
     * @return Container
     */
    public function container()
    {
        return container();
    }

    /**
     * Returns the router object
     * 
     * @return \Artemis\Core\Routing\Router
     */
    public function router()
    {
        return container('router');
    }

    /**
     * Returns the request object
     * 
     * @return \Artemis\Core\Http\Request $request
     */
    public function request()
     {
        return container('request');
    }

    /**
     * Returns the settings object
     * 
     * @return \Artemis\Core\Settings $settings
     */
    public function settings()
    {
        return container('settings');
    }

    /**
     * Returns the response object
     * 
     * @return \Artemis\Core\Http\Response $response
     */
    public function response()
    {
        return container('response');
    }

    /**
     * Returns the session object
     * 
     * @return \Artemis\Core\Session $session
     */
    public function session()
    {
        return container('session');
    }

    /**
     * Stores database settings in the settings object
     * 
     * @return void
     */
    private function storeSettings()
    {
        $db_keys = config('db_settings');

        try {
            foreach( $db_keys as $db_key ) {
                if( !$this->databaseIsActive($db_key) ) {
                    continue;
                }

                if( Schema::on($db_key)->hasTable('settings') ) {
                    $this->settings()->loadSettings($db_key);
                }
            }
        } catch(\PDOException $e) {
            report($e);
        }
    }

    /**
     * Sets the error reporting based on app debug mode.
     *
     * @return void
     */
    private function setErrorReporting()
    {
        $reporting_value = 0;

        if( $this->debug_mode ) {
            $reporting_value = E_ALL;
        }

        error_reporting($reporting_value);
    }

    /**
     * Executes application
     *
     * @throws NotFoundException|RouteException
     *
     * @return void
     */
    public function run()
    {
        $this->setErrorReporting();
        $this->storeSettings();
        $this->handleMiddlewares();

        $return = $this->router()->importRoutes()->resolve();

        if( $this->session()->isActive() && !$this->request()->isAjax() && !$this->request()->needsJson() )
            $this->session()->setLastPage();

        ResponseHandler::new($return);
    }

    /**
     * Kickstarts the middleware process for global middlewares.
     *
     * @return void
     */
    private function handleMiddlewares()
    {
        /* @var MiddlewareStack $middleware_stack */
        $middleware_stack = container(MiddlewareStack::class);

        $middleware_stack->runGlobalMiddlewares();
    }
}