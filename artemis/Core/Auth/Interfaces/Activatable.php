<?php


namespace Artemis\Core\Auth\Interfaces;


interface Activatable
{
    /**
     * Checks if the user is active
     *
     * @return bool
     */
    public function isActive();
}