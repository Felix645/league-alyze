<?php


namespace Artemis\Core\DI\Reflection;


use Artemis\Core\DI\Container;
use Artemis\Core\DI\MappingCollection;
use Artemis\Core\DI\Exceptions\InvalidParamException;
use ReflectionClass;
use ReflectionException;


class ClassReflector extends Reflector
{
    /**
     * DI Container
     *
     * @var Container
     */
    private $container;

    /**
     * A full qualified class name
     *
     * @var string
     */
    private $class;

    /**
     * Reflection of given class
     *
     * @var ReflectionClass
     */
    private $reflection;

    /**
     * Identifier if given class has a binding.
     * 
     * @var bool
     */
    private $has_binding;

    /**
     * ClassReflector constructor.
     *
     * @param Container $container
     * @param MappingCollection $collection
     * @param string $class
     * @throws ReflectionException
     */
    public function __construct($container, $collection, $class)
    {
        $this->container = $container;
        $this->collection = $collection;
        $this->class = $this->checkAlias($class);
        $this->class = $this->checkInterfaceMapping($this->class);

        $this->has_binding = $this->hasBinding();

        if( !$this->has_binding ) {
            $this->reflection = new ReflectionClass($this->class);
        }
    }

    /**
     * Creates a new instance of the class property
     *
     * @throws InvalidParamException
     * @throws ReflectionException
     *
     * @return object
     */
    public function new()
    {
        if( $this->has_binding ) {
            return $this->collection->get($this->class);
        }

        $constructor = $this->reflection->getConstructor();
        $params = $this->getParameters($constructor);

        if( empty($params) )
            return $this->checkForSingleton($this->class);

        $param_objects = [];
        foreach( $params as $param_class ) {
            if( $param_value = $this->checkDefault($param_class) ) {
                $param_objects[] = $param_value;
                continue;
            }

            if( $binding = $this->collection->getConstructorBinding($this->class, $param_class) ) {
                $param_objects[] = $this->checkBinding($binding);
                continue;
            }

            $param_objects[] = $this->getInstance($param_class);
        }

        return $this->reflection->newInstanceArgs($param_objects);
    }

    /**
     * Gets the given method call of given object from container
     *
     * @param string $method
     * @param $object
     * @throws ReflectionException
     * @throws InvalidParamException
     *
     * @return mixed
     */
    public function method($method, $object)
    {
        $reflection_method = $this->reflection->getMethod($method);
        $params = $this->getParameters($reflection_method);

        if( empty($params) )
            return $object->$method();

        $param_objects = [];
        foreach( $params as $param_class ) {
            if( $param_value = $this->checkDefault($param_class) ) {
                $param_objects[] = $param_value;
                continue;
            }

            if( $binding = $this->collection->getMethodBinding($this->class, $method, $param_class) ) {
                $param_objects[] = $this->checkBinding($binding);
                continue;
            }

            $param_objects[] = $this->getInstance($param_class);
        }

        return call_user_func_array([$object, $method], $param_objects);
    }

    /**
     * Checks if an alias is defined.
     *
     * @param string $class
     *
     * @return string
     */
    private function checkAlias($class)
    {
        $alias = $this->container->getAlias($class);

        if( null === $alias ) {
            return $class;
        }

        return $alias;
    }

    /**
     * Checks given binding and returns the bound object.
     *
     * @param $binding
     * @throws InvalidParamException
     * @throws ReflectionException
     *
     * @return object
     */
    private function checkBinding($binding)
    {
        if( !$binding->bindsToClass() ) {
            return $binding->getBinding();
        }

        return $this->buildParameterInstance($binding->getBinding());
    }

    /**
     * Checks if given class has a binding.
     * 
     * @return bool
     */
    private function hasBinding()
    {
        if( $this->collection->has($this->class) ) {
            return true;
        }

        return false;
    }
}