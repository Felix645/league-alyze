<?php


namespace Artemis\Core\Traits;


trait Singleton
{
    /**
     * Instance of the class that this trait is used in, null when there isnt an instance yet
     * 
     * @var null|self
     */
    private static $instance = NULL;

    /**
     * Returns the instance of the class that uses this trait if no instance is present yet
     * Otherwise it creates a new instance and returns it
     * 
     * @return self $instance
     */
    public static function getInstance()
    {
        if( NULL !== self::$instance ) 
            return self::$instance;
        
        self::$instance = new self();
        return self::$instance;
    }

    /**
     * Disable this so its not accessible outside of class
     */
    private function __clone() {}
}
