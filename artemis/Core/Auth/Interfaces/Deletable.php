<?php


namespace Artemis\Core\Auth\Interfaces;


interface Deletable
{
    /**
     * User is deleted or not.
     *
     * @return bool
     */
    public function isDeleted();
}