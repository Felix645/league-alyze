<?php


namespace Artemis\Core\DI;


use Artemis\Core\DI\Exceptions\InvalidParamException;
use Artemis\Core\DI\Exceptions\UnresolvableMethodException;
use Artemis\Core\DI\Reflection\CallbackReflector;
use Artemis\Core\DI\Reflection\ClassReflector;
use Artemis\Core\Exception\NotFoundException;
use Artemis\Core\Interfaces\SingletonInterface;
use Artemis\Core\Traits\Singleton;
use Closure;
use Exception;


class Container implements SingletonInterface
{
    use Singleton;

    /**
     * Alias map for classes for shorter container access.
     *
     * @var array
     */
    private $aliases = [];

    /**
     * Binding Collection
     *
     * @var MappingCollection
     */
    private $mapping_collection;

    /**
     * Container constructor.
     */
    private function __construct()
    {
        $this->mapping_collection = new MappingCollection();
    }

    /**
     * Binds a given class to a given callback
     *
     * @param string $class
     * @param Closure|string $callback
     *
     * @return void
     */
    public function bind($class, $callback)
    {
        if( $callback instanceof Closure ) {
            $binding = new Binding($this->mapping_collection, $this);
            $binding->bind($class)->give($callback);
        }

        if( is_string($callback) ) {
            $this->mapping_collection->addInterfaceBinding($class, $callback);
        }
    }

    /**
     * Binds a given class with return of given callback as a singleton
     *
     * @param string $class
     * @param Closure $callback
     *
     * @return void
     */
    public function singleton($class, $callback)
    {
        $binding = new Binding($this->mapping_collection, $this);
        $binding->bind($class)->setAsSingleton()->give($callback);
    }

    /**
     * Binds a given instance to given class.
     *
     * @param string $class
     * @param object $object
     *
     * @return void
     */
    public function instance($class, $object)
    {
        $this->mapping_collection->addInstance($class, $object);
    }

    /**
     * Starts the contextual binding process for given class.
     *
     * @param string $class
     *
     * @return Binding
     */
    public function when($class)
    {
        $binding = new Binding($this->mapping_collection, $this);
        $binding->bind($class);

        return $binding;
    }

    /**
     * Adds a class alias
     *
     * @param string $alias
     * @param string $class
     *
     * @return void
     */
    public function addAlias($alias, $class)
    {
        $this->aliases[$alias] = $class;
    }

    /**
     * Gets class for given alias.
     * Returns null if no alias is defined.
     *
     * @param string $alias
     *
     * @return string|null
     */
    public function getAlias($alias)
    {
        return $this->aliases[$alias] ?? null;
    }

    /**
     * Get instance of given class from container
     *
     * @param string $class
     *
     * @return mixed|object
     */
    public function get($class)
    {
        try {
            return (new ClassReflector($this, $this->mapping_collection, $class))->new();
        } catch(\ReflectionException | InvalidParamException $e) {
            report($e);
            exit;
        }
    }

    /**
     * Gets method call of given class from container
     *
     * @param string $class
     * @param string $method
     *
     * @return mixed
     */
    public function getWithMethod($class, $method)
    {
        try {
            $reflector = new ClassReflector($this, $this->mapping_collection, $class);
            $object = $reflector->new();

            if( !is_callable( array($object, $method) ) ) {
                throw new UnresolvableMethodException($class, $method);
            }

            return $reflector->method($method, $object);
        } catch(\ReflectionException | InvalidParamException | UnresolvableMethodException $e) {
            report($e);
            exit;
        }
    }

    /**
     * Gets given callback call from container
     *
     * @param Closure $callback
     *
     * @return mixed
     */
    public function getCallback($callback)
    {
        try {
            return (new CallbackReflector($callback, $this->mapping_collection))->new();
        } catch(\ReflectionException | InvalidParamException $e) {
            report($e);
            exit;
        }
    }
}