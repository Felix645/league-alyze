<?php


namespace Artemis\Core\Http\Interfaces\Request;


use Artemis\Core\Http\File;
use Closure;


interface HasInputsInterface
{
    /**
     * Handles the request input property access.
     *
     * @param string $name Name of the input parameter
     *
     * @return mixed Value of the input. Null if it does not exist.
     */
    public function __get($name);

    /**
     * Checks if given input parameter exists on the request.
     * Checks for multiple inputs when an array is provided for the input name.
     *
     * @param string|string[] $name Name(s) of the input.
     *
     * @return bool True if input(s) exists. False if not.
     */
    public function has($name);

    /**
     * Executes the given callback function when the given input parameter exists.
     * Checks for multiple inputs when an array is provided for the input name.
     *
     * @param string|string[] $name Name(s) of the input.
     * @param Closure $callback Callback function to be executed.
     *
     * @return void
     */
    public function whenHas($name, Closure $callback);

    /**
     * Checks if given input parameter does not exist on the request.
     * Checks for multiple inputs when an array is provided for the input name.
     *
     * @param string|string[] $name Name(s) of the input.
     *
     * @return bool Returns true if the input is missing. False if any is present.
     */
    public function missing($name);

    /**
     * Executes the given callback function when the given input parameter does not exists.
     * Checks for multiple inputs when an array is provided for the input name.
     *
     * @param string|string[] $name Name(s) of the input.
     * @param Closure $callback Callback function to be executed.
     *
     * @return void
     */
    public function whenMissing($name, Closure $callback);

    /**
     * Checks if any of the given input parameters exists.
     *
     * @param string[] $names Names of the input parameters.
     *
     * @return bool True if any of the inputs are present. False if none are present.
     */
    public function hasAny($names);

    /**
     * Executes the given callback function if any of the given input names exist.
     *
     * @param string[] $names Names of the input parameters.
     * @param Closure $callback Callback function to be executed
     *
     * @return void
     */
    public function whenHasAny($names, Closure $callback);

    /**
     * Checks if given input parameter exists on the request AND is not empty.
     * Checks for multiple inputs when an array is provided for the input name.
     *
     * @param string|string[] $name Name(s) of the input.
     *
     * @return bool True if the input is present AND NOT empty. False if not.
     */
    public function filled($name);

    /**
     * Executes the given callback function when the given input parameter exists AND is not empty.
     * Checks for multiple inputs when an array is provided for the input name.
     *
     * @param string|string[] $name Name(s) of the input.
     * @param Closure $callback Callback to be executed
     *
     * @return void
     */
    public function whenFilled($name, Closure $callback);

    /**
     * Returns only the given inputs
     *
     * @param string|array $input List of input names or first input name.
     * @param mixed ...$inputs Additional input names.
     *
     * @return array Resulting array of inputs. Returns an empty array if the parameters are invalid.
     */
    public function only($input, ...$inputs);

    /**
     * Returns all inputs except the given input names.
     *
     * @param string|array $input List of input names or first input name.
     * @param mixed ...$inputs Additional input names.
     *
     * @return array Resulting array of inputs. Returns an empty array if the parameters are invalid.
     */
    public function except($input, ...$inputs);

    /**
     * Gets the given input parameter.
     *
     * @param string $name Name of input parameter
     * @param null $default Default value for the input
     *
     * @return mixed|null Value of the input. Null or default value if input does not exist.
     */
    public function input($name, $default = null);

    /**
     * Gets the request body.
     *
     * @return array
     */
    public function all();

    /**
     * Returns an URL parameter with the given key
     *
     * @param string $key
     *
     * @return string|null $value
     */
    public function getURLParam($key);

    /**
     * Gets either an object or array from the specified file input
     *
     * @param string $key
     *
     * @return null|File|array
     */
    public function files($key);

    /**
     * Gets all files of the request. Empty array if no files are present.
     *
     * @return array
     */
    public function filesAll();

    /**
     * Adds a value to the request body
     *
     * @param string $key
     *
     * @param $value
     */
    public function addToBody($key, $value);

    /**
     * Builds a collection of URL parameters if any are defined in router and are present in request URI
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function addURLParam($key, $value);
}