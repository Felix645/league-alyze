<?php


namespace Artemis\Client\Facades;


use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;


class DB
{
    /**
     * Defines a connection and returns laravels database manager.
     *
     * @param string $connection
     *
     * @return Connection
     */
    public static function on($connection)
    {
        return Manager::connection($connection);
    }
}