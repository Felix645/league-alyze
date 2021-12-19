<?php


namespace Artemis\Core\Auth\Traits;


use Artemis\Core\Auth\Models\UserToken;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * Trait hasTokenContract
 * @package Artemis\Core\Auth\Traits
 *
 * @property UserToken $token
 */
trait hasTokenContract
{
    /**
     * Defines a 1:1 relationship to the api_auth table
     *
     * @return HasOne
     */
    public function token()
    {
        return $this->hasOne(UserToken::class, 'id_users');
    }

    /**
     * @inheritDoc
     */
    public function getToken()
    {
        $this->loadMissing(['token']);
        return $this->token->token ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getTokenExpires()
    {
        $this->loadMissing(['token']);
        return $this->token->token_expires ?? null;
    }
}