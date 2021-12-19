<?php


namespace Artemis\Core\DI\Reflection;


use Artemis\Core\DI\MappingCollection;
use Artemis\Core\DI\Exceptions\InvalidParamException;
use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;


class CallbackReflector extends Reflector
{
    /**
     * Callback function
     *
     * @var Closure
     */
    private $callback;

    /**
     * Reflection of callback
     *
     * @var ReflectionFunctionAbstract
     */
    private $reflection;

    /**
     * CallbackReflector constructor.
     *
     * @param Closure $callback
     * @param MappingCollection $collection
     * @throws ReflectionException
     */
    public function __construct($callback, $collection)
    {
        $this->callback = $callback;
        $this->collection = $collection;
        $this->reflection = $this->reflectionOfCallback($callback);
    }

    /**
     * Creates a new callback action
     *
     * @throws InvalidParamException
     * @throws ReflectionException
     *
     * @return mixed
     */
    public function new()
    {
        $params = $this->getCallbackParams();

        $param_objects = [];

        if( !empty($params) ) {
            foreach( $params as $param_class ) {
                if( $param_value = $this->checkDefault($param_class) ) {
                    $param_objects[] = $param_value;
                    continue;
                }

                $param_objects[] = $this->getInstance($param_class);
            }
        }

        return call_user_func_array($this->callback, $param_objects);
    }

    /**
     * Gets the reflection class of a callback function
     *
     * @param callable $callable
     * @throws ReflectionException
     *
     * @return ReflectionMethod|ReflectionFunction
     */
    private function reflectionOfCallback($callable)
    {
        if ($callable instanceof Closure)
            return new ReflectionFunction($callable);

        if (is_string($callable)) {
            $pcs = explode('::', $callable);
            return count($pcs) > 1 ? new ReflectionMethod($pcs[0], $pcs[1]) : new ReflectionFunction($callable);
        }

        if (!is_array($callable))
            $callable = [$callable, '__invoke'];

        return new ReflectionMethod($callable[0], $callable[1]);
    }

    /**
     * Gets callback parameters
     *
     * @throws InvalidParamException
     *
     * @return array
     */
    private function getCallbackParams()
    {
        $params = $this->reflection->getParameters();

        $callback_params = [];

        foreach ($params as $param) {
            $callback_params[] = $this->evaluateParameter($param);
        }

        return $callback_params;
    }
}