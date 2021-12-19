<?php


namespace Artemis\Core\Auth\Models;


use Artemis\Client\Eloquent;
use Artemis\Core\Auth\Interfaces\Activatable;
use Artemis\Core\Auth\Interfaces\Authenticatable as AuthenticationContract;
use Artemis\Core\Auth\Interfaces\AdminAuthentication;
use Artemis\Core\Auth\Interfaces\Bannable;
use Artemis\Core\Auth\Interfaces\Deletable;
use Artemis\Core\Auth\Interfaces\LdapAuthentication;
use Artemis\Core\Auth\Interfaces\TokenAuthentication;
use Artemis\Core\Auth\Traits\hasActivatableContract;
use Artemis\Core\Auth\Traits\hasAdminContract;
use Artemis\Core\Auth\Traits\hasAuthenticationContract;
use Artemis\Core\Auth\Traits\hasBannableContract;
use Artemis\Core\Auth\Traits\hasDeletableContract;
use Artemis\Core\Auth\Traits\hasLdapContract;
use Artemis\Core\Auth\Traits\hasTokenContract;


abstract class Authenticatable extends Eloquent implements
    AuthenticationContract,
    LdapAuthentication,
    TokenAuthentication,
    AdminAuthentication,
    Activatable,
    Bannable,
    Deletable
{
    use hasAuthenticationContract,
        hasAdminContract,
        hasLdapContract,
        hasTokenContract,
        hasActivatableContract,
        hasBannableContract,
        hasDeletableContract;

    public $primaryKey = 'ID';
    protected $table = 'users';
    public $timestamps = false;
}