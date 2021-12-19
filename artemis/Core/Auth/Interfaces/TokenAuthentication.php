<?php


namespace Artemis\Core\Auth\Interfaces;


interface TokenAuthentication
{
    /**
     * Gets the user authentication token
     *
     * @return string|null
     */
    public function getToken();

    /**
     * Gets the expiration date of the authentication token
     *
     * @return string|null
     */
    public function getTokenExpires();
}