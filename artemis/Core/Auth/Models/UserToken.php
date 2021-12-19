<?php


namespace Artemis\Core\Auth\Models;


use Artemis\Client\Eloquent;
use Artemis\Core\Auth\Interfaces\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class UserToken
 * @package Artemis\Core\Auth\Models
 *
 * @property int $id
 * @property int $id_users
 * @property string $token
 * @property string $token_expires
 * @property string $timestamp_lastchange
 * @property AuthenticatableContract $user
 */
class UserToken extends Eloquent
{
    public $primaryKey = 'id';
    protected $table = 'api_auth';
    public $timestamps = false;

    protected $fillable = [
        'id_users',
        'token',
        'token_expires'
    ];

    /**
     * Defines a 1:1 relationship to the api_auth table
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AuthenticatableContract::class, 'id_users');
    }
}