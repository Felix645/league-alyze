<?php


namespace Artemis\Core\Middleware\Middlewares;


use Artemis\Core\Exception\ExpiredException;
use Artemis\Core\Http\Request;
use Artemis\Core\Interfaces\MiddlewareInterface;
use Artemis\Core\Session;


class CSRFMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     * @throws ExpiredException|\Artemis\Core\Exception\ConfigurationException
     */
    public function execute()
    {
        if( !config('csrf_protection') )
            return;

        /* @var Request $request */
        $request = container('request');

        /* @var Session $session */
        $session = container('session');

        if( $request->getRequestMethod() === 'get' )
            return;

        $request_token = $request->getCSRFToken();
        $session_token = $session->getCSRFToken();
        $expires = $session->getCSRFExpires();

        if( empty($request_token) || $request_token !== $session_token || $expires < now() )
            throw new ExpiredException();
    }
}