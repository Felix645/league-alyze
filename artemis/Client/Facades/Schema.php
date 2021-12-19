<?php


namespace Artemis\Client\Facades;


use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;


class Schema
{
    /**
     * Defines a connection and returns schema builder.
     *
     * @param string $connection
     *
     * @return Builder
     */
    public static function on($connection)
    {
        return Manager::schema($connection);
    }
}