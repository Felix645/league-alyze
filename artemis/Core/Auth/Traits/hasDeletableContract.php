<?php


namespace Artemis\Core\Auth\Traits;


trait hasDeletableContract
{
    /**
     * Column name that holds the information if the user is deleted or not
     *
     * @var string
     */
    private $deleted_field = 'deleted';

    /**
     * @inheritDoc
     */
    public function isDeleted()
    {
        return boolval($this->{$this->deleted_field});
    }
}