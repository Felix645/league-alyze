<?php

namespace Artemis\Core\Auth\Access;

use Artemis\Core\Auth\Access\Exceptions\GateException;
use Artemis\Core\Auth\Interfaces\Authenticatable;
use Artemis\Core\Exception\ForbiddenException;
use Artemis\Support\Arr;
use Closure;

class GateManager
{
    /**
     * Collection of defined gates.
     *
     * @var array
     */
    private $gates = [];

    /**
     * Closure to be executed before all gates.
     *
     * @var null|Closure
     */
    private $before = null;

    /**
     * Closure to be executed after all gates.
     *
     * @var null|Closure
     */
    private $after = null;

    /**
     * Defines a new gate.
     *
     * @param string $gate_key      Key of the gate.
     * @param Closure|array $action Action of the gate. Can be a closure or an array containing a class string and a method name.
     *
     * @return void
     */
    public function define(string $gate_key, $action) : void
    {
        if( isset($this->gates[$gate_key]) ) {
            $message = 'Attempt to overwrite existing gate';
            report(new GateException($gate_key, $message));
        }

        if( !$action instanceof Closure && !is_array($action) ) {
            $message = 'Invalid gate defintion. Second parameter of definition only allows closures and arrays';
            report(new GateException($gate_key, $message));
        }

        $this->gates[$gate_key] = $action;
    }

    /**
     * Checks if the given gate is allowed.
     *
     * @param string $gate_key              Key of the gate.
     * @param null|mixed|array $arguments   Additional arguments. Multiple arguments have to be passed via an array.
     *
     * @return bool TRUE if the action is allowed. FALSE otherwise.
     */
    public function allows(string $gate_key, $arguments = null) : bool
    {
        if( !$this->userIsAuthenticated() ) {
            return false;
        }

        $this->checkGateExistence($gate_key);

        $return = $this->callGate($gate_key, $this->prependUserToArguments($arguments));

        return $this->checkBoolean($return);
    }

    /**
     * Checks if the given gate is denied.
     *
     * @param string $gate_key              Key of the gate.
     * @param null|mixed|array $arguments   Additional arguments. Multiple arguments have to be passed via an array.
     *
     * @return bool TRUE if the action is NOT allowed. FALSE otherwise.
     */
    public function denies(string $gate_key, $arguments = null) : bool
    {
        return !$this->allows($gate_key, $arguments);
    }

    /**
     * Checks if any of the given gates is allowed.
     *
     * @param array $gates      List of gate key's to be checked.
     * @param null|mixed|array $arguments   Additional arguments. Multiple arguments have to be passed via an array.
     *                                      ALL listed gates get the listed arguments as parameters.
     *
     * @return bool TRUE if at least one of the given gates is allowed. FALSE otherwise.
     */
    public function any(array $gates, $arguments = null) : bool
    {
        if( !$this->userIsAuthenticated() ) {
            return false;
        }

        $gates_count = Arr::length($gates);

        foreach( $gates as $gate_key ) {
            $this->checkGateExistence($gate_key);

            $return = $this->callGate($gate_key, $this->prependUserToArguments($arguments));

            if( !$return ) {
                $gates_count--;
            }
        }

        return $gates_count > 0;
    }

    /**
     * Checks if ALL the given gates are denied.
     *
     * @param array $gates                  List of gate key's to be checked.
     * @param null|mixed|array $arguments   Additional arguments. Multiple arguments have to be passed via an array.
     *                                      ALL listed gates get the listed arguments as parameters.
     *
     * @return bool TRUE if ALL the given gates are denied. FALSE otherwise.
     */
    public function none(array $gates, $arguments = null) : bool
    {
        if( !$this->userIsAuthenticated() ) {
            return false;
        }

        $gates_count = Arr::length($gates);

        foreach( $gates as $gate_key ) {
            $this->checkGateExistence($gate_key);

            $return = $this->callGate($gate_key, $this->prependUserToArguments($arguments));

            if( !$return ) {
                $gates_count--;
            }
        }

        return $gates_count <= 0;
    }

    /**
     * Checks if the given gate is allowed. If not a ForbiddenException will be thrown.
     *
     * @param string $gate_key              Key of the gate.
     * @param null|mixed|array $arguments   Additional arguments. Multiple arguments have to be passed via an array.
     *
     * @throws ForbiddenException
     *
     * @return void
     */
    public function authorize(string $gate_key, $arguments = null)
    {
        if( $this->allows($gate_key, $arguments) ) {
            return;
        }

        throw new ForbiddenException();
    }

    /**
     * Defines a closure to be executed BEFORE all gates.
     *
     * @param Closure $action Closure containing the authorization logic.
     *
     * @return void
     */
    public function before(Closure $action)
    {
        $this->before = $action;
    }

    /**
     * Defines a closure to be executed AFTER all gates.
     *
     * @param Closure $action Closure containing the authorization logic.
     *
     * @return void
     */
    public function after(Closure $action)
    {
        $this->after = $action;
    }

    /**
     * Checks if the given gate_key exists.
     * Reports an exception to the error handler if it doesn't exist.
     *
     * @param string $gate_key Gate Key to be checked.
     *
     * @return void
     */
    private function checkGateExistence(string $gate_key) : void
    {
        if( !isset($this->gates[$gate_key]) ) {
            report(new GateException($gate_key, 'Attempt to call non-existent gate'));
        }
    }

    /**
     * Checks the given value and converts it to an expected boolean.
     *
     * @param mixed $value Value to be checked.
     *
     * @return bool
     */
    private function checkBoolean($value) : bool
    {
        if( $value === null || $value === true ) {
            return true;
        }

        return false;
    }

    /**
     * Calls the given gate with the given arguments.
     *
     * @param string $gate_key Key of the gate to be called.
     * @param array $arguments List of arguments.
     *
     * @return bool TRUE if the gate is allowed, FALSE otherwise.
     */
    private function callGate(string $gate_key, array $arguments) : bool
    {
        if( !is_null($this->before) ) {
            $before_return = call_user_func_array($this->before, $this->getBeforeArguments($gate_key));

            if( null !== $before_return ) {
                return (bool)$before_return;
            }
        }

        $action = $this->gates[$gate_key];

        if( $action instanceof Closure ) {
            $gate_result = call_user_func_array($this->gates[$gate_key], $arguments);

            if( null !== $this->after ) {
                $after_return = call_user_func_array($this->after, $this->getAfterArguments($gate_key, $gate_result));

                if( null !== $after_return ) {
                    return (bool)$after_return;
                }
            }

            return $gate_result;
        }

        if( !is_array($action) ) {
            $message = 'Invalid gate defintion. Second parameter of definition only allows closures and arrays';
            report(new GateException($gate_key, $message));
        }

        if( empty($action[0]) || empty($action[1]) || !is_string($action[0]) || !is_string($action[1]) ) {
            $message = 'Invalid gate definition. Second parameter has not a valid array format';
            report(new GateException($gate_key, $message));
        }

        $action_class = $action[0];

        if( !class_exists($action_class) ) {
            $message = "Invalid gate definition. Class '$action_class' is not defined";
            report(new GateException($gate_key, $message));
        }

        $action_object = container($action_class);

        $gate_result = call_user_func_array([$action_object, $action[1]], $arguments);

        if( null !== $this->after ) {
            $after_return = call_user_func_array($this->after, $this->getAfterArguments($gate_key, $gate_result));

            if( null !== $after_return ) {
                return (bool)$after_return;
            }
        }

        return (bool)$gate_result;
    }

    /**
     * Prepends the current user to the list of arguments.
     *
     * @param mixed|array $arguments Arguments to passed to the gate.
     *
     * @return array List of arguments, ready to be used for call_user_func_array.
     */
    private function prependUserToArguments($arguments) : array
    {
        $first = $this->getUser();

        if( is_array($arguments) ) {
            array_unshift($arguments, $first);

            $finished_arguments = $arguments;
        } else {
            $finished_arguments[] = $first;

            if( !is_null($arguments) ) {
                $finished_arguments[] = $arguments;
            }
        }

        return $finished_arguments;
    }

    /**
     * Builds the argument list for the before-Closure.
     *
     * @param string $gate_key Key of the gate that has been called.
     *
     * @return array List of arguments.
     */
    private function getBeforeArguments(string $gate_key) : array
    {
        return [$this->getUser(), $gate_key];
    }

    /**
     * Builds the argument list for the after-Closure.
     *
     * @param string $gate_key Key of the gate that has been called.
     * @param mixed $result Result of the gate that has been called.
     *
     * @return array List of arguments.
     */
    private function getAfterArguments(string $gate_key, $result) : array
    {
        $arguments = $this->getBeforeArguments($gate_key);
        $arguments[] = $result;

        return $arguments;
    }

    /**
     * Checks if the current user is authenticated.
     *
     * @return bool TRUE if the user is authenticated, FALSE otherwise.
     */
    private function userIsAuthenticated() : bool
    {
        return auth()->valid && auth()->user() instanceof Authenticatable;
    }

    /**
     * Gets the authenticated user.
     *
     * @return Authenticatable
     */
    private function getUser() : Authenticatable
    {
        return auth()->user();
    }
}