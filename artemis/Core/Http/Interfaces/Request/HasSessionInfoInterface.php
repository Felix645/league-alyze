<?php


namespace Artemis\Core\Http\Interfaces\Request;


interface HasSessionInfoInterface
{
    /**
     * Gets the last visited page
     *
     * @return string
     */
    public function getLastPage();

    /**
     * Gets the CSRF-Token
     *
     * @return string|null
     */
    public function getCSRFToken();
}