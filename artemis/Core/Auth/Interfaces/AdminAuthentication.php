<?php


namespace Artemis\Core\Auth\Interfaces;


interface AdminAuthentication
{
    /**
     * Checks if the user is an admin
     *
     * @return bool
     */
    public function isAdmin();
}