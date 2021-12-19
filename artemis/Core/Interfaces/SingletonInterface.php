<?php


namespace Artemis\Core\Interfaces;


interface SingletonInterface
{
    /**
     * Returns instance of the class
     * 
     * @return mixed
     */
    public static function getInstance();
}