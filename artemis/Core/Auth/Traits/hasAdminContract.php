<?php


namespace Artemis\Core\Auth\Traits;


trait hasAdminContract
{
    /**
     * Column name of that hold the information if the user is admin or not
     *
     * @var string
     */
    private $admin_field = 'admin';

    public function isAdmin()
    {
        return boolval($this->{$this->admin_field});
    }
}