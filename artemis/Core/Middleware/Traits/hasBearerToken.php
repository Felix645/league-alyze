<?php


namespace Artemis\Core\Middleware\Traits;


trait hasBearerToken
{
    /**
     * The bearer request token
     *
     * @var string
     */
    protected $request_token;

    /**
     * Gets the bearer token from the authorization header
     *
     * @return bool
     */
    protected function requestBearerToken()
    {
        $bearer = container('request')->bearerToken();

        if( empty($bearer) ) {
            return false;
        }

        $this->request_token = $bearer;

        return true;
    }
}