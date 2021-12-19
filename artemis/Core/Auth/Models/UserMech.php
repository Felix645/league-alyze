<?php


namespace Artemis\Core\Auth\Models;


use Artemis\Client\Eloquent;
use Artemis\Core\Auth\Interfaces\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class UserMech
 * @package Artemis\Core\Auth\Models
 *
 * @property int $id
 * @property int $users_id
 * @property bool $mechanism_id
 * @property int $ldap_settings_id
 * @property int $status
 * @property bool $active
 * @property string $timestamp_insert
 * @property string $timestamp_lastchange
 * @property AuthenticatableContract $user
 * @property LdapSetting $ldap_setting
 */
class UserMech extends Eloquent
{
    public const CREATED_AT = 'timestamp_insert';
    public const UPDATED_AT = 'timestamp_lastchange';

    public $primaryKey = 'id';
    protected $table = 'users_auth_mech';

    protected $casts = [
        'mechanism_id'  => 'boolean',
        'active'        => 'boolean'
    ];

    protected $fillable = [
        'users_id',
        'mechanism_id',
        'ldap_settings_id',
        'status',
        'active',
        'timestamp_insert',
        'timestamp_lastchange'
    ];

    /**
     * Defines a 1:1 relationship to the users table
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AuthenticatableContract::class, 'users_id');
    }

    /**
     * Defines a 1:1 relationship to the ldap_settings table
     *
     * @return BelongsTo
     */
    public function ldap_setting()
    {
        return $this->belongsTo(LdapSetting::class, 'ldap_settings_id');
    }
}