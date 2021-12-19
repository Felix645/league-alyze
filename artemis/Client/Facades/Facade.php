<?php


namespace Artemis\Client\Facades;


use Artemis\Client\Facades\Exceptions\FacadeException;


abstract class Facade
{
    /**
     * Collection of accessors
     * 
     * @var array
     */
    private static $accessors = [];

    /**
     * Adds accessor => class map
     *
     * @param string $accessor
     * @param string $class
     *
     * @return void
     */
    public static function addAccessor($accessor, $class)
    {
        self::$accessors[$accessor] = $class;
    }

    /**
     * Gets the accessor, instantiates the corresponding object and calls the method
     * 
     * @param string $method
     * @param array $arguments
     * 
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        try {
            if( !isset(self::$accessors[static::getAccessor()]) ) {
                $accessor = static::getAccessor();
                $message = "Accessor '$accessor' is not defined";
                throw new FacadeException($message);
            }

            $accessor = self::$accessors[static::getAccessor()];

            $instance = container(self::$accessors[static::getAccessor()]);

            if( !method_exists($instance, $method) ) {
                $message = "Method '$method' does not exists in class '$accessor'";
                throw new FacadeException($message);
            }

            return call_user_func_array([$instance, $method], $arguments);
        } catch( FacadeException $e ) {
            report($e);
            exit;
        }
    }

    /**
     * Returns the accessors to the abstract Facade
     * 
     * @return string
     */
    abstract protected static function getAccessor();
}

