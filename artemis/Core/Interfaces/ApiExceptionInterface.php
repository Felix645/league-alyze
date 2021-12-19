<?php


namespace Artemis\Core\Interfaces;


interface ApiExceptionInterface
{
    /**
     * Gets the API response for this exception
     *
     * @return string
     */
    public function getResponse();
}