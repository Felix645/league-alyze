<?php


namespace Artemis\Core;


use Artemis\Support\Arr;
use Artemis\Client\Facades\Hash;
use Closure;


class Session
{
    /**
     * Identifier if the session is active
     *
     * @var bool
     */
    private $is_active = false;

    /**
     * Clearing identifier to prevent Alerts from being cleared when set to true
     * 
     * @var bool
     */
    private $preventClear = FALSE;

    /**
     * CSRF-Token
     * 
     * @var null|string
     */
    private $csrf_token = null;

    /**
     * CSRF-Token expiration date
     * 
     * @var null|string
     */
    private $csrf_expires = null;

    /**
     * Session keys that can be flushed
     *
     * @var string[]
     */
    private $clearable = [
        'alert',
        'form_data'
    ];

    /**
     * Session configuration array.
     *
     * @var array
     */
    private $session_config;

    /**
     * Starts the session
     *
     * @return void
     */
    public function startSession()
    {
        session_start();
        $this->is_active = true;
        $this->session_config = require_once ROOT_PATH . 'config/session.php';

        if( !isset($_SESSION["last_page"]) )
            $_SESSION["last_page"] = '';

        if( config('csrf_protection') ) {
            $this->setCSRFToken();
        } else {
            if( isset($_SESSION["artemis"]["csrf_token"]) || isset($_SESSION["artemis"]["csrf_expires"]) ) {
                unset($_SESSION["artemis"]["csrf_token"]);
                unset($_SESSION["artemis"]["csrf_expires"]);
            }
        }
    }

    /**
     * Gets the session configuration array.
     *
     * @return array
     */
    public function config()
    {
        return $this->session_config;
    }

    /**
     * Gets the whole session array.
     *
     * @return array
     */
    public function all()
    {
        return $_SESSION ?? [];
    }

    /**
     * Gets a value from the session.
     * Optionally a default value may be provided
     *
     * @param string $key
     * @param string|Closure $default
     *
     * @return mixed
     */
    public function get($key, $default = '')
    {
        if( is_string($default) && !empty($default) )
            return $_SESSION[$key] ?? $default;

        if( is_callable($default) )
            return $_SESSION[$key] ?? $default();

        return $_SESSION[$key] ?? null;
    }

    /**
     * Gets a value from the session with one additional dimension.
     * Optionally a default value may be provided
     *
     * @param string $key
     * @param string $second_key
     * @param string|Closure $default
     *
     * @return mixed
     */
    public function pull($key, $second_key, $default = '')
    {
        if( is_string($default) && !empty($default) )
            return $_SESSION[$key][$second_key] ?? $default;

        if( is_callable($default) )
            return $_SESSION[$key][$second_key] ?? $default();

        return $_SESSION[$key][$second_key] ?? null;
    }

    /**
     * Adds a key/value pair to the session
     *
     * @param string|int $key
     * @param mixed $value
     *
     * @return $this
     */
    public function put($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Adds a key/value pair to the session with one additional dimension
     *
     * @param string|int $key
     * @param string|int $second_key
     * @param mixed $value
     *
     * @return $this
     */
    public function push($key, $second_key, $value)
    {
        $_SESSION[$key][$second_key] = $value;
        return $this;
    }

    /**
     * Adds a flash for the next request to the session
     *
     * @param string $key
     *
     * @param $value
     */
    public function flash($key, $value)
    {
        $_SESSION[$key] = $value;
        $this->clearable[] = $key;
        $this->preventClear = true;
    }

    /**
     * Prevents the current flush cycle
     *
     * @return void
     */
    public function preventClear()
    {
        $this->preventClear = true;
    }

    /**
     * Deletes a given key from the session
     *
     * @param string $key
     *
     * @return $this
     */
    public function delete($key) : Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * Checks if given key exists in the session AND is not null.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Checks if given key exists in the session
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return Arr::exists($key, $_SESSION);
    }


    /**
     * Gets the form data from the previous request
     * 
     * @return array
     */
    public function getOldFormData()
    {
        return $_SESSION['form_data'] ?? [];
    }

    /**
     * Checks if there is an active session
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Adds a new alert to the session variables
     * 
     * @param string $type
     * @param string $key
     * @param string $message
     * 
     * @return void
     */
    public function addAlert($type, $key, $message)
    {
        if( !$this->isActive() ) {
            return;
        }

        $alert = new Alert($key, $message);

        $_SESSION["alert"][$type][$key] = $alert;

        $this->preventClear = true;
    }

    /**
     * Gets the all alerts from specified type
     * 
     * @param string $type
     * 
     * @return Alert[]
     */
    public function getAlerts($type)
    {
        return $_SESSION["alert"][$type] ?? [];
    }

    /**
     * Gets a alert
     * 
     * @param string $type
     * @param string $key
     * 
     * @return Alert|null
     */
    public function getAlert($type, $key)
    {
        return $_SESSION["alert"][$type][$key] ?? null;
    }

    /**
     * Sets the last visited page
     * 
     * @return void
     */
    public function setLastPage()
    {
        $route = container('request')->getRequestURI();
        $_SESSION["last_page"] = $route;
    }

    /**
     * Gets the last visited page
     * 
     * @return string
     */
    public function getlastPage()
    {
        return $_SESSION["last_page"];
    }

    /**
     * Gets the CSRF-Token
     * 
     * @return string|null
     */
    public function getCSRFToken()
    {
        return $this->csrf_token;
    }

    /**
     * Gets the CSRF-Token expiration date
     * 
     * @return string|null
     */
    public function getCSRFExpires()
    {
        return $this->csrf_expires;
    }

    /**
     * Sets the csrf token if it is not set yet
     * 
     * @return void
     */
    private function setCSRFToken()
    {
        $csrf_token = $_SESSION["artemis"]["csrf_token"] ?? null;
        $csrf_expires = $_SESSION["artemis"]["csrf_expires"] ?? null;

        if( isset($csrf_token) && isset($csrf_expires) ) {
            if( $csrf_expires > now() ) {
                $this->csrf_token = $csrf_token;
                $this->csrf_expires = $csrf_expires;
                return;
            }

        }

        $_SESSION["artemis"]["csrf_token"] = Hash::hexToken(20);
        $_SESSION["artemis"]["csrf_expires"] = now(config('csrf_expiration_time'));

        $this->csrf_token = $_SESSION["artemis"]["csrf_token"];
        $this->csrf_expires = $_SESSION["artemis"]["csrf_expires"];
    }

    /**
     * Adds the user id to the session
     *
     * @param string $db
     * @param int $id
     *
     * @return void
     */
    public function setUserID($db, $id)
    {
        $_SESSION[$db][config('auth_session_id')] = $id;
    }

    /**
     * Gets the user id from the session
     *
     * @param string $db
     *
     * @return int|null
     */
    public function getUserID($db)
    {
        return $_SESSION[$db][config('auth_session_id')] ?? null;
    }

    /**
     * Adds the user token to the session
     *
     * @param string $db
     * @param string $token
     *
     * @return void
     */
    public function setUserToken($db, $token)
    {
        $_SESSION[$db][config('auth_session_token')] = $token;
    }

    /**
     * Gets the user token from the session
     *
     * @param string $db
     *
     * @return string|null
     */
    public function getUserToken(string $db)
    {
        return $_SESSION[$db][config('auth_session_token')] ?? null;
    }

    /**
     * Clears the auth session with the given database key
     *
     * @param string $db
     *
     * @return void
     */
    public function clearAuthSession($db)
    {
        unset($_SESSION[$db]);
    }

    /**
     * Destroy the whole session
     * 
     * @return void
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * Flushes clearable session values
     *
     * @return $this
     */
    public function flush()
    {
        foreach( $this->clearable as $key ) {
            if( isset($_SESSION[$key]) )
                unset($_SESSION[$key]);
        }

        return $this;
    }

    /**
     * Destructor, clears session variables of any clearable entries
     */
    public function __destruct() 
    {
        if( !$this->preventClear ) {
            $this->flush();
        }
    }
}