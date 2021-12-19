<?php


namespace Artemis\Core\Auth\Interfaces;


interface Bannable
{
    /**
     * User is banned or not.
     *
     * @return bool
     */
    public function isBanned();
}