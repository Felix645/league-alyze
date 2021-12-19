<?php


namespace Artemis\Core\Http;


use Artemis\Support\Arr;
use Artemis\Support\Str;
use Artemis\Core\Http\Exceptions\RequestException;
use Artemis\Core\Http\Interfaces\ContentTypes;
use Artemis\Core\Http\Interfaces\HttpHeaders;
use Artemis\Core\Http\Interfaces\HttpStatusCodes;
use Artemis\Core\Http\Interfaces\Request\HasHeadersInterface;
use Artemis\Core\Http\Interfaces\Request\HasInputsInterface;
use Artemis\Core\Http\Interfaces\Request\HasRequestInfoInterface;
use Artemis\Core\Http\Interfaces\Request\HasSessionInfoInterface;
use Artemis\Core\Http\Interfaces\Request\HasValidationInterface;
use Artemis\Core\Routing\RouteValidator;
use Closure;


abstract class RequestContract implements
    HasHeadersInterface,
    HasInputsInterface,
    HasRequestInfoInterface,
    HasValidationInterface,
    HasSessionInfoInterface,
    HttpStatusCodes,
    HttpHeaders,
    ContentTypes
{
    /**
     * @inheritDoc
     *
     * @return mixed Value of the input. Null if it does not exist.
     */
    public function __get($name)
    {
        $body = $this->all();

        if( !Arr::exists($name, $body) ) {
            return null;
        }

        return $body[$name];
    }

    /**
     * Gets the request body.
     *
     * @return array
     */
    public function body()
    {
        return $this->all();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if input(s) exists. False if not.
     */
    public function has($name)
    {
        if( is_string($name) ) {
            return isset($this->all()[$name]);
        }

        if( is_array($name) ) {
            foreach( $name as $input_name ) {
                if( !$this->has($input_name) ) {
                    return false;
                }
            }

            return true;
        }

        report(new RequestException("Invalid parameter type provided in RequestContract::has()"));
        exit;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function whenHas($name, Closure $callback)
    {
        if( !$this->has($name) ) {
            return;
        }

        $this->handleInputCheckCallbackReturn($callback($this));
    }

    /**
     * @inheritDoc
     *
     * @return bool Returns true if the input is missing. False if any is present.
     */
    public function missing($name)
    {
        if( is_string($name) ) {
            return !isset($this->all()[$name]);
        }

        if( is_array($name) ) {
            foreach( $name as $input_name ) {
                if( $this->has($input_name) ) {
                    return false;
                }
            }

            return true;
        }

        report(new RequestException("Invalid parameter type provided in RequestContract::missing()"));
        exit;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function whenMissing($name, Closure $callback)
    {
        if( !$this->missing($name) ) {
            return;
        }

        $this->handleInputCheckCallbackReturn($callback($this));
    }

    /**
     * @inheritDoc
     *
     * @return bool True if any of the inputs are present. False if none are present.
     */
    public function hasAny($names)
    {
        foreach( $names as $name ) {
            if( $this->has($name) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function whenHasAny($names, Closure $callback)
    {
        if( !$this->hasAny($names) ) {
            return;
        }

        $this->handleInputCheckCallbackReturn($callback($this));
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the input is present AND NOT empty. False if not.
     */
    public function filled($name)
    {
        if( is_string($name) ) {
            return !empty($this->all()[$name]);
        }

        if( is_array($name) ) {
            foreach( $name as $input_name ) {
                if( !$this->filled($input_name) ) {
                    return false;
                }
            }

            return true;
        }

        report(new RequestException("Invalid parameter type provided in RequestContract::filled()"));
        exit;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function whenFilled($name, Closure $callback)
    {
        if( !$this->filled($name) ) {
            return;
        }

        $this->handleInputCheckCallbackReturn($callback($this));
    }

    /**
     * @inheritDoc
     *
     * @return array Resulting array of inputs. Returns an empty array if the parameters are invalid.
     */
    public function only($input, ...$inputs)
    {
        if( is_array($input) ) {
            return Arr::intersectKeys($this->all(), $input);
        }

        if( is_string($input) ) {
            $needles[] = $input;
            return Arr::intersectKeys($this->all(), array_merge($needles, $inputs));
        }

        return [];
    }

    /**
     * @inheritDoc
     *
     * @return array Resulting array of inputs. Returns an empty array if the parameters are invalid.
     */
    public function except($input, ...$inputs)
    {
        if( is_array($input) ) {
            return Arr::removeKeys($this->all(), $input);
        }

        if( is_string($input) ) {
            $needles[] = $input;
            return Arr::removeKeys($this->all(), array_merge($needles, $inputs));
        }

        return [];
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the pattern matches, false if not.
     */
    public function like($path)
    {
        $segments = $this->getURLBits();
        $path_bits = explode('/', Str::trim($path, '/'));

        $matched_bits = 0;
        $found_wildcard = false;

        foreach( $segments as $index => $segment )  {
            if( $found_wildcard ) {
                $matched_bits++;
                continue;
            }

            if( !isset($path_bits[$index]) ) {
                return false;
            }

            if( '*' === $path_bits[$index] ) {
                $found_wildcard = true;
                $matched_bits++;
                continue;
            }

            if( $path_bits[$index] === $segment ) {
                $matched_bits++;
                continue;
            }

            return false;
        }

        if( $matched_bits === Arr::length($segments) && $matched_bits >= Arr::length($path_bits) ) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the methods match, false if they don't.
     */
    public function isMethod($method)
    {
        return Str::lower($method) === $this->getRequestMethod();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the request matches the route, false if it does not.
     */
    public function likeRoute($route_name)
    {
        $route = app()->router()->getRoutes()->getByName($route_name);

        if( !$route ) {
            return false;
        }

        return RouteValidator::match($route, $this->getURLBits());
    }

    /**
     * @inheritDoc
     * 
     * @return mixed|null Value of the input. Null or default value if input does not exist.
     */
    public function input($name, $default = null)
    {
        return $this->all()[$name] ?? $default;
    }

    /**
     * Handles the return of callback function like whenFilled().
     *
     * @param mixed $return Return value of the callback.
     *
     * @return void
     */
    private function handleInputCheckCallbackReturn($return)
    {
        if( !empty($return) ) {
            ResponseHandler::new($return);
        }
    }
}