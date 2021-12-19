<?php


namespace Artemis\Core\DI\Reflection;


use Artemis\Core\DI\MappingCollection;
use Artemis\Core\DI\Exceptions\InvalidParamException;
use Artemis\Core\Interfaces\SingletonInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;


abstract class Reflector
{
    /**
     * Binding Collection
     *
     * @var MappingCollection
     */
    protected $collection;

    /**
     * Checks if given parameter is a valid class
     *
     * @param $paramClass
     *
     * @return bool
     */
    protected function validateParamClass($paramClass)
    {
        if( is_null($paramClass) || (!class_exists($paramClass) && !interface_exists($paramClass)) ) {
            return false;
        }

        return true;
    }

    /**
     * Creates a new instance of given parameter
     *
     * @param string $class
     * @return object
     *
     * @throws InvalidParamException|ReflectionException
     *
     */
    protected function buildParameterInstance($class)
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        $params = $this->getParameters($constructor);

        if( empty($params) )
            return $this->checkForSingleton($class);

        $param_objects = [];
        foreach( $params as $param_class ) {
            $param_objects[] = $this->buildParameterInstance($param_class);
        }

        $reflection = new ReflectionClass($class);

        return $reflection->newInstanceArgs($param_objects);
    }

    /**
     * @param ReflectionMethod|null $method
     * @throws InvalidParamException
     *
     * @return array
     */
    protected function getParameters($method)
    {
        if( !isset($method) )
            return [];

        $params = $method->getParameters();

        $param_names = [];

        foreach( $params as $param ) {
            $param_names[] = $this->evaluateParameter($param);
        }

        return $param_names;
    }

    /**
     * Creates a new class instance depending whether it implements the SingletonInterface
     *
     * @param $class
     *
     * @return object
     */
    protected function checkForSingleton($class)
    {
        if( is_subclass_of($class, SingletonInterface::class) )
            return $class::getInstance();
        else
            return new $class();
    }

    /**
     * Checks if there is an implementation mapping for given method parameter
     *
     * @param string $param_class
     *
     * @return string
     */
    protected function checkInterfaceMapping($param_class)
    {
        if( $map = $this->collection->getInterfaceBinding($param_class) ) {
            return $map;
        }

        return $param_class;
    }

    /**
     * Builds route parameter with given parameter info.
     *
     * @param array $param_info
     *
     * @return mixed
     */
    protected function buildRouteParam($param_info)
    {
        $value = request()->getURLParam($param_info['name']);
        $type = $param_info['type'];

        if( !is_null($type) ) {
            settype($value, $type);
        }

        return $value;
    }

    /**
     * Evaluates given parameter reflection.
     *
     * @param ReflectionParameter $param
     * @throws InvalidParamException
     *
     * @return mixed
     */
    protected function evaluateParameter($param)
    {
        $param_type = $param->getType();

        if( $param_type instanceof ReflectionNamedType ) {
            $param_type_name = $param_type->getName();

            if( $this->validateParamClass($param_type_name) ) {
                return $param_type_name;
            }

            if( !is_null(request()->getURLParam($param->getName())) ) {
                return $this->getRouteParamArray($param_type_name, $param);
            }

            throw new InvalidParamException($param, $param_type);
        }

        if( !is_null(request()->getURLParam($param->getName())) ) {
            return $this->getRouteParamArray(null, $param);
        }

        throw new InvalidParamException($param);
    }

    /**
     * Builds the route parameter info array.
     *
     * @param mixed $type
     * @param ReflectionParameter $param
     *
     * @return array
     */
    private function getRouteParamArray($type, $param)
    {
        return [
            'type' => $type,
            'name' => $param->getName()
        ];
    }

    /**
     * Checks for binding collection and route parameters.
     *
     * @param mixed $param_class
     *
     * @return mixed|object|null
     */
    protected function checkDefault($param_class)
    {
        if( is_array($param_class) ) {
            return $this->buildRouteParam($param_class);
        }

        if( $this->collection->has($param_class) ) {
            return $this->collection->get($param_class);
        }

        return null;
    }

    /**
     * Builds parameter instance from given class string.
     *
     * @param $param_class
     * @throws InvalidParamException
     * @throws ReflectionException
     *
     * @return object
     */
    protected function getInstance($param_class)
    {
        $param_class = $this->checkInterfaceMapping($param_class);
        return $this->buildParameterInstance($param_class);
    }
}