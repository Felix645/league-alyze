<?php


namespace Artemis\Core\Auth\Traits;


trait hasBannableContract
{
    /**
     * Column name that hold the information if the user in banned or not.
     *
     * @var string
     */
    private $banned_field = 'banned';

    /**
     * @inheritDoc
     */
    public function isBanned()
    {
        return boolval($this->{$this->banned_field});
    }
}