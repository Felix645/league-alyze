<?php


namespace Artemis\Core\Auth\Traits;


trait hasActivatableContract
{
    /**
     * Column name that holds the 'active' information
     *
     * @var string
     */
    private $active_field = 'active';

    /**
     * @inheritDoc
     */
    public function isActive()
    {
        return boolval($this->{$this->active_field});
    }
}