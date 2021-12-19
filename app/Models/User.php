<?php


namespace App\Models;


use Artemis\Core\Auth\Models\Authenticatable;


class User extends Authenticatable
{
    public $primaryKey = 'ID';
    protected $table = 'users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'active',
        'admin',
        'banned',
        'deleted',
        'reset_expires'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password_hash',
        'password_salt',
        'reset_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active'        => 'boolean',
        'admin'         => 'boolean',
        'banned'        => 'boolean',
        'deleted'       => 'boolean',
        'reset_expires' => 'datetime'
    ];
}