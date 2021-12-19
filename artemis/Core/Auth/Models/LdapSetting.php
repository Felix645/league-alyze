<?php


namespace Artemis\Core\Auth\Models;


use Artemis\Client\Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class LdapSetting
 * @package Artemis\Core\Auth\Models
 *
 * @property int $id
 * @property string $name
 * @property string $sys_auth_mech_ldap_server
 * @property string $sys_auth_mech_ldap_dn
 * @property string $sys_auth_mech_ldap_pwd
 * @property string $sys_auth_mech_ldap_basedn
 * @property string $domain
 * @property string $timestamp_insert
 * @property string $timestamp_lastchange
 * @property UserMech $user_mech
 */
class LdapSetting extends Eloquent
{
    public const CREATED_AT = 'timestamp_insert';
    public const UPDATED_AT = 'timestamp_lastchange';

    public $primaryKey = 'id';
    protected $table = 'ldap_settings';

    /**
     * Defines a 1:1 relationship with the users_auth_mech table
     *
     * @return BelongsTo
     */
    public function user_mech()
    {
        return $this->belongsTo(UserMech::class, 'ldap_settings_id');
    }
}