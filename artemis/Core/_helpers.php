<?php


use Kint\Kint;
use Kint\Renderer\RichRenderer;
use Artemis\Core\Exception\ConfigurationException;


if( !function_exists('container') ) {
    /**
     * Returns the instance of the apps DI container.
     * Optionally a class name may be defined to get an instance of the class from the container
     *
     * @param string $class_name
     *
     * @return mixed|object
     */
    function container($class_name = '')
    {
        $container = \Artemis\Core\DI\Container::getInstance();

        if( empty($class_name) )
            return $container;

        return $container->get($class_name);
    }
}


if( !function_exists('collect') ) {
    /**
     * Transform the given array into a laravel collection object.
     *
     * @param array $array
     *
     * @return \Illuminate\Support\Collection
     */
    function collect($array)
    {
        return \Illuminate\Support\Collection::make($array);
    }
}


if( !function_exists('app') ) {
    /**
     * Gets the framework application instance
     *
     * @return \Artemis\Application
     */
    function app()
    {
        return container('app');
    }
}


if( !function_exists('env') ) {
    /**
     * Gets a specified environment variable
     * 
     * @param string $key
     * @param mixed $default
     * 
     * @return string|bool
     */
    function env($key, $default = null)
    {
        if( !isset($_ENV[$key]) ) {
            if( !is_null($default) ) {
                return $default;
            }

            $message = "Environment variable '$key' is not defined";
            report(new \Artemis\Core\Exception\EnvironmentException($message));
            exit;
        }

        if( 'true' === \Artemis\Support\Str::lower($_ENV[$key]) || 'false' === \Artemis\Support\Str::lower($_ENV[$key]) )
            return filter_var($_ENV[$key], FILTER_VALIDATE_BOOLEAN);

        return $_ENV[$key];
    }
}


if( !function_exists('auth') ) {
    /**
     * Returns the authenticate object
     * optionally a database key may be defined (for example for logging in)
     * additionally to the database key an auth method may be defined (method is 'default') by default,
     * Available methods: default, token, curl
     * 
     * @param string $db
     * 
     * @return null|\Artemis\Core\Auth\Auth
     */
    function auth($db = '')
    {
        if( empty($db) )
            return container('auth_manager')->get();

        return container('auth_manager')->new($db);
    }
}


if( !function_exists('config') ) {
    /**
     * Gets the configuration from the resource Config Class
     * 
     * @param string $key
     * 
     * @return mixed
     */
    function config($key)
    {
        $config = require ROOT_PATH . 'config/config.php';

        if( !isset($config[$key]) ) {
            $message = "Configuration '$key' doesn't exists!";
            report(new ConfigurationException($message));
            exit;
        }

        return $config[$key];
    }
}


if( !function_exists('route') ) {
    /**
     * Gets the route uri with the specified name.
     * Optionally route parameters may be specified as the second argument.
     * Optionally a full url may be returned by adding 'true' as the third argument
     *
     * @param string $name
     * @param array $params
     * @param bool $full_url
     *
     * @return \Artemis\Utils\RouteBuilder
     */
    function route($name, $params = [], $full_url = false)
    {
        $routeBuilder = new \Artemis\Utils\RouteBuilder($name, $params);

        if( $full_url )
            return $routeBuilder->full();

        return $routeBuilder;
    }
}


if( !function_exists('settings') ) {
    /**
     * Gets a setting from the specified db_key and settings key
     *
     * @param string $db
     * @param string $key
     *
     * @throws \Artemis\Core\Database\Exceptions\DatabaseException
     *
     * @return string
     */
    function settings($db, $key)
    {
        return app()->settings()->get($db, $key);
    }
}


if( !function_exists('dd') ) {
    /**
     * Dumps the given variable and ends execution of the script
     *
     * @param mixed ...$dump_values
     *
     * @return void
     */
    function dd(...$dump_values)
    {
        if( request()->needsJson() ) {
            sdd(...$dump_values);
        }

        RichRenderer::$folder = false;
        Kint::dump($dump_values);
        exit(1);
    }
}


if( !function_exists('sdd') ) {
    /**
     * Dumps the given variable and ends execution of the script with a simple presentation.
     *
     * @param mixed ...$dump_values
     *
     * @return void
     */
    function sdd(...$dump_values)
    {
        echo '<pre>';
        var_dump(...$dump_values);
        echo '</pre>';

        exit(1);
    }
}


if( !function_exists('d') ) {
    /**
     * Dumps the given variable
     *
     * @param $dump_values
     *
     * @return void
     */
    function d($dump_values)
    {
        Kint::dump($dump_values);
    }
}


if( !function_exists('redirect') ) {
    /**
     * Returns the redirector object
     * If parameter is set it will redirect to the given uri
     * 
     * @param string $uri
     * 
     * @return \Artemis\Core\Interfaces\RedirectionInterface
     */
    function redirect($uri = '')
    {
        if( config('legacy_redirects') ) {
            /* @var $redirector \Artemis\Core\Interfaces\RedirectionInterface */
            $redirector = new \Artemis\Core\Http\LegacyRedirector();

            if( !empty($uri) ) {
                $url = app()->domain() . $uri;
                $redirector->url($url);
                exit;
            }

            return $redirector;
        }

        /* @var $redirector \Artemis\Core\Interfaces\RedirectionInterface */
        $redirector = container('redirect');

        if( !empty($uri) ) {
            $url = app()->domain() . $uri;
            $redirector->url($url);
            exit;
        }

        return $redirector;
    }
}


if( !function_exists('now') ) {
    /**
     * Returns the current timestamp, or a modified timestamp
     * 
     * @param string $modifier
     * 
     * @return string
     */
    function now($modifier = '')
    {
        $now = (new \Artemis\Core\Date\Date())->now();

        if( !empty($modifier) )
            $now->modify($modifier);
  
        return $now->get();
    }
}


if( !function_exists('today') ) {
    /**
     * Gets todays date as a string
     *
     * @param string $format
     * @param string $modifier
     *
     * @return string
     */
    function today($format = 'Y-m-d', $modifier = '')
    {
        $now = (new \Artemis\Core\Date\Date())->now()->format($format);

        if( !empty($modifier))
            $now->modify($modifier);

        return $now->get();
    }
}


if( !function_exists('dateTime') ) {
    /**
     * Gets a artemis Date instance
     *
     * @param string $input
     *
     * @return \Artemis\Core\Date\Date
     */
    function dateTime($input = 'now')
    {
        if( empty($input) )
            return new \Artemis\Core\Date\Date();

        return new \Artemis\Core\Date\Date($input);
    }
}


if( !function_exists('view') ) {
    /**
     * Returns the view object with view and data
     * 
     * @param string $view
     * @param array $data
     * 
     * @return \Artemis\Core\Template\View
     */
    function view($view, $data = [])
    {
        try {
            return container('view')->setView($view)->setData($data);
        } catch(Throwable $e) {
            report($e);
            exit;
        }
    }
}


if( !function_exists('api') ) {
    /**
     * Returns the API response helper
     *
     * @return \Artemis\Utils\API
     */
    function api() : \Artemis\Utils\API
    {
        return container('api');
    }
}


if( !function_exists('asset') ) {
    /**
     * Returns an url of given asset based on the ASSET_URL defined in .env
     *
     * @param string $asset
     *
     * @return string
     */
    function asset(string $asset) : string
    {
        $asset_url = \Artemis\Support\Str::trimRight(app()->assets(), '/') . '/';
        $asset = \Artemis\Support\Str::trimLeft($asset, '/');
        return $asset_url . $asset;
    }
}


if( !function_exists('session') ) {
    /**
     * Gets the session instance by default.
     * By specify $option as a string a value from the session may be retrieved.
     * Optionally a $default value may be defined (string, int, float or callback function).
     * Values can be added by defining key/value pairs inside an array for the $option argument
     *
     * @param string $option
     * @param string $default
     *
     * @return mixed|\Artemis\Core\Session
     */
    function session($option = '', $default = '')
    {
        $session = container('session');

        if( empty($option) )
            return $session;

        if( is_string($option) ) {
            if( !empty($default) && (is_string($default) || is_array($default) || is_callable($default)) )
                return $session->get($option, $default);

            return $session->get($option);
        }

        if( is_array($option) ) {
            foreach( $option as $key => $value ) {
                $session->put($key, $value);
            }

            return $session;
        }

        return $session;
    }
}


if( !function_exists('old') ) {
    /**
     * Returns a form data value from the previous request.
     * Returns an empty string if no value was found.
     * 
     * @param string $key
     * 
     * @return mixed
     */
    function old(string $key)
    {
        $old_form_data = container('session')->getOldFormData();
        return $old_form_data[$key] ?? '';
    }
}


if( !function_exists('request') ) {
    /**
     * Gets a Http/Client/Request instance.
     * Returns value of request input if a key is provided, returns null if input for key does not exist.
     *
     * @param string $key
     *
     * @return \Artemis\Core\Http\Request|null|mixed
     */
    function request(string $key = '')
    {
        /** @var \Artemis\Core\Http\RequestContract $request */
        $request = container('request');

        if( empty($key) )
            return $request;

        return $request->input($key);
    }
}


if( !function_exists('response') ) {
    /**
     * Returns a Http/Client/Response instance.
     *
     * @return \Artemis\Core\Http\Response
     */
    function response() : \Artemis\Core\Http\Response
    {
        return container('response');
    }
}


if( !function_exists('sanitize') ) {
    /**
     * Sanitizes a given string to be outputted in a page
     *
     * @param string $data
     *
     * @return string
     */
    function sanitize($data)
    {
        return htmlspecialchars($data, ENT_QUOTES);
    }
}


if( !function_exists('error') ) {
    /**
     * Gets either a single error or a collection of errors
     *
     * @param string $key
     *
     * @return \Artemis\Core\Alert|\Artemis\Core\Alert[]|null
     */
    function error($key = '')
    {
        if( !empty($key) )
            return container('session')->getAlert('error', $key);

        return container('session')->getAlerts('error');
    }
}


if( !function_exists('success') ) {
    /**
     * Gets either a single success or a collection of success
     *
     * @param string $key
     *
     * @return \Artemis\Core\Alert|\Artemis\Core\Alert[]|null
     */
    function success($key = '')
    {
        if( !empty($key) ) {
            return container('session')->getAlert('success', $key);
        }

        return container('session')->getAlerts('success');
    }
}


if( !function_exists('validation') ) {
    /**
     * Gets a validation error
     *
     * @param string $key
     *
     * @return \Artemis\Core\Alert|null
     */
    function validation($key)
    {
        return container('session')->getAlert('validation', $key);
    }
}


if( !function_exists('csrf_field') ) {
    /**
     * Gets csrf field if csrf_protection is enabled.
     * Otherwise returns an empty string.
     *
     * @return string
     */
    function csrf_field() : string
    {
        if( config('csrf_protection') )
            return '<input type="hidden" name="_csrf" value="'.container('session')->getCSRFToken().'">';

        return '';
    }
}


if( !function_exists('event') ) {
    /**
     * Dispatches given event.
     *
     * @param string|object $event
     *
     * @return void
     */
    function event($event)
    {
        container('event')->dispatch($event);
    }
}


if( !function_exists('cache') ) {
    /**
     * Gets the cache instance.
     *
     * @return \Artemis\Core\Cache\CachingInterface
     */
    function cache()
    {
        return container(\Artemis\Core\Cache\CachingInterface::class);
    }
}


if( !function_exists('validations_exist') ) {
    /**
     * Checks if one of the given validation keys is present.
     *
     * @param mixed ...$validation_keys
     *
     * @return bool
     */
    function validations_exist(...$validation_keys) : bool
    {
        foreach( $validation_keys as $key ) {
            if( validation($key) ) {
                return true;
            }
        }

        return false;
    }
}


if( !function_exists('report') ) {
    /**
     * Reports an exception to the handler.
     *
     * @param Throwable $e
     *
     * @return void
     */
    function report(Throwable $e)
    {
        container(\Artemis\Resource\Providers\ExceptionServiceProvider::class)->report($e);
    }
}

