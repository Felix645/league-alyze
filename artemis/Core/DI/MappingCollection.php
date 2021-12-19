<?php


namespace Artemis\Core\DI;


class MappingCollection
{
    /**
     * Collection of simple bindings
     *
     * @var Binding[]
     */
    private $bindings;

    /**
     * Collection of bound singletons
     *
     * @var Singleton[]
     */
    private $singletons;

    /**
     * Collection of interface binding without context
     *
     * @var array
     */
    private $interfaces;

    /**
     * Collection of bindings for __construct parameters
     *
     * @var array
     */
    private $construct_bindings;

    /**
     * Collection of bindings for method parameters
     *
     * @var array
     */
    private $method_bindings;

    /**
     * Adds a simple binding
     *
     * @param Binding $binding
     *
     * @return void
     */
    public function addBinding($binding)
    {
        $this->bindings[$binding->getBindingClass()] = $binding;
    }

    /**
     * Adds a singleton
     *
     * @param Binding $binding
     *
     * @return void
     */
    public function addSingleton($binding)
    {
        $singleton = new Singleton();
        $singleton->addBinding($binding);
        $this->singletons[$binding->getBindingClass()] = $singleton;
    }

    /**
     * Binds an existing instance to given class
     *
     * @param string $class
     * @param object $object
     *
     * @return void
     */
    public function addInstance($class, $object)
    {
        $singleton = new Singleton();
        $singleton->addInstance($object);
        $this->singletons[$class] = $singleton;
    }

    /**
     * Adds a constructor binding
     *
     * @param Binding $binding
     *
     * @return void
     */
    public function addConstructBinding($binding)
    {
        $this->construct_bindings[$binding->getBindingClass()][] = $binding;
    }

    /**
     * Adds a class method binding
     *
     * @param Binding $binding
     *
     * @return void
     */
    public function addMethodBinding($binding)
    {
        $this->method_bindings[$binding->getBindingClass()][] = $binding;
    }

    /**
     * Binds an implementation to a given interface.
     *
     * @param string $interface
     * @param string $implementation
     *
     * @return void
     */
    public function addInterfaceBinding($interface, $implementation)
    {
        $this->interfaces[$interface] = $implementation;
    }

    /**
     * Checks if given class is present in either simple or singleton bindings
     *
     * @param string $class
     *
     * @return bool
     */
    public function has($class)
    {
        return isset($this->bindings[$class]) || isset($this->singletons[$class]);
    }

    /**
     * Returns normal or singleton binding for given class
     *
     * @param string $class
     *
     * @return object|null
     */
    public function get($class)
    {
        return isset($this->singletons[$class])
            ? ($this->singletons[$class])->get()
            : ($this->bindings[$class])->getBinding();
    }

    /**
     * Returns binding for given class and need.
     * Returns null if no binding was found.
     *
     * @param string $class
     * @param string $needs
     *
     * @return Binding|null
     */
    public function getConstructorBinding($class, $needs)
    {
        if( !isset($this->construct_bindings[$class]) ) {
            return null;
        }

        /* @var Binding $binding */
        foreach( $this->construct_bindings[$class] as $binding ) {
            if( $binding->givesToClass($class) && $binding->givesToNeed($needs) ) {
                return $binding;
            }
        }

        return null;
    }

    /**
     * Returns binding for given class, method and need.
     * Returns null if no binding was found.
     *
     * @param string $class
     * @param string $method
     * @param string $needs
     *
     * @return Binding|null
     */
    public function getMethodBinding($class, $method, $needs)
    {
        if( !isset($this->method_bindings[$class]) ) {
            return null;
        }

        /* @var Binding $binding */
        foreach( $this->method_bindings[$class] as $binding ) {
            if( $binding->givesToClass($class) && $binding->givesToNeed($needs) && $binding->givesToMethod($method) ) {
                return $binding;
            }
        }

        return null;
    }

    /**
     * Gets an interface binding.
     * Returns null if binding was not found.
     *
     * @param string $interface
     *
     * @return string|null
     */
    public function getInterfaceBinding($interface)
    {
        return $this->interfaces[$interface] ?? null;
    }
}