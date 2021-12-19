<?php


namespace Artemis\Core\DI;


use Closure;


class Binding
{
    /**
     * Method of class to be bound
     *
     * @var null|string
     */
    private $method = null;

    /**
     * Class to be bound
     *
     * @var null|string
     */
    private $class = null;

    /**
     * What is to be injected
     *
     * @var null|string
     */
    private $needs = null;

    /**
     * The actual binding return
     *
     * @var null|Closure|string
     */
    private $bind = null;

    /**
     * Binding Collection
     *
     * @var MappingCollection
     */
    private $collection;

    /**
     * Container Instance.
     * 
     * @var Container
     */
    private $container;

    /**
     * Identifier if this bind is a singleton or not
     *
     * @var bool
     */
    private $is_singleton = false;

    /**
     * Identifier if this binds to a class string or to a closure
     *
     * @var bool
     */
    private $binds_to_class = true;

    /**
     * Binding constructor.
     *
     * @param MappingCollection $collection
     * @param Container $container
     */
    public function __construct($collection, $container)
    {
        $this->collection = $collection;
        $this->container = $container;
    }

    /**
     * Sets what class is to be bound
     *
     * @param string $class
     *
     * @return $this
     */
    public function bind($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Sets what method for given class is to be bound
     *
     * @param string $method
     *
     * @return $this
     */
    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Sets what is to be injected
     *
     * @param string $class
     *
     * @return $this
     */
    public function needs($class)
    {
        $this->needs = $class;
        return $this;
    }

    /**
     * Sets the actual binding return
     *
     * @param $input
     *
     * @return void
     */
    public function give($input)
    {
        if( $input instanceof Closure ) {
            $this->binds_to_class = false;
        }

        $this->bind = $input;
        $this->checkBindingState();
    }

    /**
     * Sets the bind to singleton
     *
     * @return $this
     */
    public function setAsSingleton()
    {
        $this->is_singleton = true;
        return $this;
    }

    /**
     * Gets the actual binding
     *
     * @return Closure|mixed|string|null
     */
    public function getBinding()
    {
        if( !$this->binds_to_class) {
            return ($this->bind)($this->container);
        }

        return $this->bind;
    }

    /**
     * Checks if this binds to a class or a closure
     *
     * @return bool
     */
    public function bindsToClass()
    {
        return $this->binds_to_class;
    }

    /**
     * Checks if given need is bound
     *
     * @param string $need
     *
     * @return bool
     */
    public function givesToNeed($need)
    {
        return $need === $this->needs;
    }

    /**
     * Checks if given class is bound
     *
     * @param string $class
     *
     * @return bool
     */
    public function givesToClass($class)
    {
        return $class === $this->class;
    }

    /**
     * Checks if given method is bound
     *
     * @param string $method
     *
     * @return bool
     */
    public function givesToMethod($method)
    {
        return $method === $this->method;
    }

    /**
     * Gets the bound class
     *
     * @return string
     */
    public function getBindingClass()
    {
        return $this->class;
    }

    /**
     * Checks the current binding state and adds this object to the corresponding collection
     *
     * @return void
     */
    private function checkBindingState()
    {
        if( null !== $this->method ) {
            $this->collection->addMethodBinding($this);
            return;
        }

        if( null !== $this->needs ) {
            $this->collection->addConstructBinding($this);
            return;
        }

        if( $this->is_singleton ) {
            $this->collection->addSingleton($this);
            return;
        }

        $this->collection->addBinding($this);
    }
}